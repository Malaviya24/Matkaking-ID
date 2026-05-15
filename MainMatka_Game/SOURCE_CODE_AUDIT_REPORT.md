# MainMatka Source Code Audit Report

Date: 2026-05-03  
Scope: `MainMatka_Game` PHP source, including user login/register, betting, wallet, withdrawal, deposit, result display, admin panel, and shared helpers.

## Executive Summary

The original audit found core production risks in wallet integrity, result payout processing, credential handling, authentication/session security, and missing CSRF protection on user money-moving forms.

As of 2026-05-03, the 4 Critical and 7 High findings below have been remediated in source. Medium and Low findings remain separate follow-up work unless noted by later changes.

PHP syntax lint passed for the checked PHP files outside `__MACOSX`, so the main problems are logic, security, and business-flow bugs rather than parse errors.

## Remediation Status

- C1 Fixed: `include/connect.php` now requires database settings from environment or ignored local config, and `.env.example` contains placeholders only. Exposed live credentials still need external rotation.
- C2 Fixed: betting, withdrawal, admin balance adjustments, admin deposit approvals, and result credits now run through transactions with user row locks; bet totals are computed server-side.
- C3 Fixed: `admin/results.php` now provides main/starline result entry and settlement, locks the market and pending bids, credits winners, and avoids double-crediting settled bids.
- C4 Fixed: default admin credentials were removed; env admin login works only when `MAINMATKA_ADMIN_USERNAME` and `MAINMATKA_ADMIN_PASSWORD_HASH` are explicitly set.
- H1 Fixed: new and reset passwords use `password_hash`; login/change/admin auth use `password_verify` with one-time legacy MD5 migration.
- H2 Fixed: user money-moving and betting POST forms now require `app_validate_csrf()` tokens.
- H3 Fixed: auth cookies are set through one helper with `HttpOnly`, `SameSite=Lax`, and `Secure` on HTTPS; auth tokens use `random_bytes`, and weak random helpers use `random_int`.
- H4 Fixed: login redirects now pass through `app_safe_return_path()` and reject external hosts or invalid decoded targets.
- H5 Fixed: session restoration now revalidates the user token/status against the database and clears blocked sessions/cookies centrally.
- H6 Fixed: withdrawal POST enforces Sunday, min/max, current balance, and winnings-derived withdrawable amount under a row lock; the UI now shows the same withdrawable calculation.
- H7 Fixed: the misspelled bank-details token was removed and replaced with the shared CSRF token.

## Critical

### C1. Live credentials and service secrets are hard-coded in source

Evidence:
- `include/connect.php:16-18` contains default database host/user/password values.
- `include/connect.php:27` contains a default SMS auth key.
- `include/connect.php:29-31` points to a service-account JSON path.
- `include/connect.php:21` connects to the database immediately using those defaults.

Impact:
- Anyone with the source can access the configured database or third-party services unless the credentials have already been rotated.
- Local test runs can accidentally mutate the remote/default database.
- This is especially dangerous because wallet, deposit, and user records are real-money data.

Recommended fix:
- Remove all real defaults from source.
- Require environment variables for database, SMS, and service-account config.
- Rotate the exposed database/SMS/FCM credentials.
- Add a safe local `.env.example` with placeholder values only.

### C2. Wallet debits/credits are not atomic and can lose money or overspend

Evidence:
- `include/functions.php:94-97` updates balance directly with no transaction or row lock.
- `single.php:40`, `jodi.php:40`, `single-patti.php:40`, `double-patti.php:40`, `triple-patti.php:40`, `half-sangam.php:49`, `full-sangam.php:46` check balance before placing bets.
- `single.php:64-68`, `jodi.php:75-78`, and similar betting pages update balance first, then insert `user_transaction`.
- `withdraw.php:39-53` checks balance, updates balance, then inserts withdraw transaction without a transaction.
- Starline pages repeat the same pattern, for example `starline-single.php:34-60`.

Impact:
- Two simultaneous requests can read the same balance and both pass checks.
- Balance can be deducted even when the transaction insert fails.
- Bet placement can be partially recorded if a loop places some rows then fails later.
- Withdrawals can race against bets/deposits and create inconsistent balances.

Recommended fix:
- Wrap every wallet mutation in a DB transaction.
- Use `SELECT ... FOR UPDATE` on the user row.
- Insert the ledger transaction and update the user balance in the same transaction.
- Never trust client-side `total_point`; compute the total from validated posted bet rows server-side.

### C3. Normal market result payout engine appears missing

Evidence:
- Result display reads from `result` in `include/functions.php:610` and `include/functions.php:637`.
- User history expects `user_transaction.win` to be populated in `bidding-history.php:67-72`.
- The only located result insertion and payout processor is the temporary test-market code in `admin/test-market.php:150-233`.
- No normal admin result workflow was found that inserts normal results and credits all winning bids.

Impact:
- Normal market results can display without users being credited.
- Bets may remain pending forever unless another external system updates them.
- Admin dashboards can show bet totals, but settlement is not guaranteed by this codebase.

Recommended fix:
- Build a single production result-settlement service for main and starline markets.
- For each result, lock pending bids for that market/date, calculate wins by game type, update `win`, insert `type='win'` credit rows, and update balances in one transaction.
- Make result insertion idempotent so re-running a result cannot double-credit winners.

### C4. Default admin login exists unless environment overrides it

Evidence:
- `admin/includes/bootstrap.php:347-357` accepts environment admin credentials with defaults.
- Default username/password path is active before DB-admin login.

Impact:
- If deployed without overriding env vars, `/admin/login.php` has a known default credential path.
- This can give full admin access to balances, deposits, user blocking, notifications, and password resets.

Recommended fix:
- Remove default admin credentials.
- Require `MAINMATKA_ADMIN_PASSWORD_HASH` or a DB admin user.
- Add rate limiting and audit logging for admin login.

## High

### H1. Passwords use MD5 and weak minimum length

Evidence:
- `login.php:73` hashes login passwords with `md5`.
- `register.php:87` stores new user passwords with `md5`.
- `change-password.php:14` and `change-password.php:42` verify/store with `md5`.
- `admin/user.php:91` resets user passwords with `md5`.
- `admin/includes/bootstrap.php:366` checks DB admin passwords with `md5`.

Impact:
- MD5 password hashes are fast to crack if the user table leaks.
- Existing 4-character minimum on reset paths is too weak.

Recommended fix:
- Use `password_hash($password, PASSWORD_DEFAULT)` and `password_verify`.
- Migrate existing MD5 hashes on next successful login.
- Raise minimum length and add password reset audit logging.

### H2. User money-moving forms do not use CSRF protection

Evidence:
- User forms such as `add-fund.php:24`, `withdraw.php:9`, `change-password.php:12`, `change-password.php:28`, and betting forms such as `single.php:12` rely only on a logged-in session.
- Admin forms do have CSRF, for example `admin/includes/bootstrap.php:44-56`.

Impact:
- A logged-in user can be tricked into submitting deposit requests, withdrawal requests, password changes after verification, bank-detail changes, or bets.

Recommended fix:
- Add a shared user CSRF helper like the admin helper.
- Require CSRF tokens on every user POST.
- Use SameSite cookies as an additional defense, but do not rely on cookies alone.

### H3. Auth cookies are long-lived and missing security flags

Evidence:
- `login.php:87-90` and `register.php:158-161` set identity and API token cookies for 30 days.
- No `HttpOnly`, `Secure`, or `SameSite` options are set.
- `include/functions.php:745-752` generates API tokens with `rand`, not `random_bytes`.

Impact:
- Client-side scripts can read auth cookies.
- Cookies can be sent cross-site.
- Tokens are weaker than they should be for account authentication.

Recommended fix:
- Use PHP session cookies with `HttpOnly`, `Secure`, and `SameSite=Lax/Strict`.
- Generate tokens with `bin2hex(random_bytes(32))`.
- Avoid storing redundant identity fields in cookies.

### H4. Login return URL can redirect to an arbitrary decoded target

Evidence:
- `include/session.php:50` builds `return_url` from the full current URL.
- `login.php:103` redirects to `base64_decode($return_url)` without restricting host/path.

Impact:
- If an attacker can craft a login URL with an external base64 target, login can become an open redirect.
- This can be used for phishing and token-handoff tricks.

Recommended fix:
- Only allow relative paths beginning with `/` or known local filenames.
- Reject decoded URLs with a scheme/host.

### H5. Blocked-user enforcement is incomplete on pages that bypass `include/session.php`

Evidence:
- `include/session.php:34-44` correctly checks current status and logs out blocked users.
- `index.php:17`, `game-rates.php:18`, `starline-play.php:19`, `top-winner-list.php:18`, and `top-winner-list-starline.php:18` restore sessions from cookies by checking only `id` and `api_access_token`.

Impact:
- Blocked users can still access parts of the logged-in app if those pages use the duplicated cookie-refresh logic instead of `include/session.php`.

Recommended fix:
- Replace duplicated auth logic with `include/session.php` everywhere a logged-in page is required.
- Centralize session restore, status check, and logout behavior.

### H6. Withdrawal rules do not match the UI/business text

Evidence:
- `withdraw.php:17` uses lifetime `SUM(win)` to decide whether the user has any winning amount.
- `withdraw.php:39-53` permits withdrawing from current balance, not from a tracked withdrawable winnings balance.
- `withdraw.php:141` says Sunday withdrawals are off, but the POST handler has no Sunday check.
- `withdraw.php:154` has a UI max of `10000`, but the server has no max check.

Impact:
- A user with any historical win can withdraw deposited balance.
- Sunday and max-withdrawal limits can be bypassed with a direct POST.
- Multiple concurrent withdrawals can race and overdraw without row locking.

Recommended fix:
- Track separate `deposit_balance`, `winning_balance`, or a derived withdrawable ledger.
- Enforce min/max/day rules server-side.
- Process withdrawal requests with a transaction and row lock.

### H7. Bank-details token check is broken by a misspelled session key

Evidence:
- `update-bank-details.php:11-13` compares posted `api_acess_token` against `$_SESSION['api_acess_token']`.
- The real session key elsewhere is `api_access_token`.
- `update-bank-details.php:152` renders the same misspelled key, usually empty.

Impact:
- The intended token check is effectively useless.
- This also adds confusion because it looks like a security control while not validating the real session token.

Recommended fix:
- Replace this with proper CSRF.
- Remove the misspelled token field.

## Medium

### M1. Normal result display is not gated by result time

Evidence:
- `index.php:479-488` reads open/close result values directly.
- `index.php:554-559` displays result times separately, but the result display does not check those times.

Impact:
- If a result row is inserted early, users can see it immediately instead of at the scheduled result time.

Recommended fix:
- Only display open/close result after `result_open_time` / `result_close_time`.
- Keep admin result entry separate from public visibility.

### M2. Overnight or late-night market timing is fragile

Evidence:
- `game-dashboard.php:64-80` compares open/close times using today's date only.
- `single.php:36-48` and other play pages compare the selected game time using today's date only.
- `include/functions.php:109-114` has `check_late_night`, but it is not used in the main time checks.

Impact:
- Markets crossing midnight can be marked closed or invalid at the wrong time.

Recommended fix:
- Normalize market windows with start/end datetimes.
- Use the `late_night` flag or explicit close date calculation for overnight markets.

### M3. Result helper functions can read null rows and use uninitialized totals

Evidence:
- `include/functions.php:613-617`, `include/functions.php:639-643`, `include/functions.php:658-662`, and `include/functions.php:677-681` dereference result objects and add into `$ank` without initializing it.
- `include/connect.php:2-3` suppresses errors globally.

Impact:
- Missing results can trigger warnings/notices that are hidden in production.
- Hidden PHP notices make result bugs harder to diagnose.

Recommended fix:
- Return placeholder values when no row exists before reading `$value->digit`.
- Initialize `$ank = 0`.
- Log errors instead of globally suppressing all errors.

### M4. Balance rounding floors every wallet balance lookup

Evidence:
- `include/functions.php:401-406` returns `floor($value->balance)`.

Impact:
- Decimal balances are silently rounded down.
- Admin deposits/payouts using decimal values can display or process differently from stored values.

Recommended fix:
- Treat wallet values as fixed decimal numbers.
- Return `round((float)$balance, 2)` or use integer paise/points consistently.

### M5. `game_id` and parent/child relation are not validated on bet POST

Evidence:
- `single.php:25`, `jodi.php:25`, and similar pages trust posted `game_id`.
- Hidden `gid`, `pgid`, and `dgame` are also trusted for redirects and page state.

Impact:
- A direct POST can submit bets against a different child game than the visible page intended.
- It is easier to bypass UI market restrictions.

Recommended fix:
- Load the selected game from DB and verify it belongs to the posted parent.
- Verify `type` matches open/close rules.
- Compute redirect parameters from DB, not from POST.

### M6. Deposit UTR uniqueness is race-prone

Evidence:
- `add-fund.php:42-45` checks duplicate UTR with a `LIKE` query before insert.
- `add-fund.php:49-51` inserts the request separately without a transaction or unique DB key.

Impact:
- Two simultaneous requests can submit the same UTR.
- `LIKE 'Manual Deposit UTR: {$utr_no}%'` can match unintended suffixes.

Recommended fix:
- Add a dedicated `utr_no` column with a unique index.
- Insert with a transaction or handle duplicate-key errors.

### M7. User input is escaped manually instead of using prepared statements

Evidence:
- `register.php:126-143`, `login.php:77`, `update-bank-details.php:47-60`, betting pages, and many helpers build SQL strings manually.
- Admin code uses prepared statements in some places, but the public app largely does not.

Impact:
- Manual escaping is easy to miss or apply incorrectly.
- Type bugs and injection risk remain across future changes.

Recommended fix:
- Move repeated SQL into prepared helper functions.
- Cast numeric IDs/amounts explicitly and bind all values.

### M8. Global SSL verification is disabled in HTTP helper paths

Evidence:
- `include/functions.php:792-793` disables SSL host and peer verification.
- `include/functions.php:1010` disables SSL peer verification for OneSignal path.

Impact:
- Outbound API calls can be intercepted without detection.

Recommended fix:
- Enable SSL verification.
- Configure trusted CA bundles if the host environment needs them.

## Low

### L1. PHP errors are globally hidden

Evidence:
- `include/connect.php:2-3` disables error reporting and display.

Impact:
- Logic bugs such as undefined `$res`, missing DB rows, or bad result values can fail silently.

Recommended fix:
- Keep display off in production, but log errors.
- Use stricter local development error settings.

### L2. Betting pages can return failed when no valid bet amount was posted

Evidence:
- `single.php:64-76`, `jodi.php:75-87`, and similar pages set `$res` only inside the valid amount branch, then check `if($res)`.

Impact:
- Empty or invalid direct POSTs produce inconsistent behavior and hidden notices.

Recommended fix:
- Initialize `$res = false`.
- Count accepted bet rows and reject requests with zero valid rows before any wallet mutation.

### L3. Generated token randomness uses `rand`

Evidence:
- `include/functions.php:745-752` uses `rand` in `generateRandomString`.

Impact:
- Tokens are less unpredictable than they should be for auth/session use.

Recommended fix:
- Use `bin2hex(random_bytes(32))`.

### L4. Old macOS metadata files are committed

Evidence:
- `__MACOSX` PHP metadata files appear throughout the tree, for example `MainMatka_Game/__MACOSX/._login.php`.

Impact:
- Source tree is noisy and may confuse scanners or deployment scripts.

Recommended fix:
- Remove `__MACOSX`.
- Add `__MACOSX/` and `._*` to `.gitignore`.

### L5. Public pages duplicate auth/session code

Evidence:
- `index.php:7-27`, `login.php:7-27`, `register.php:7-27`, `game-rates.php:7-27`, and `starline-play.php:7-29` repeat cookie/session restore logic.

Impact:
- Security fixes can land in one copy but not others.
- This already caused inconsistent blocked-user behavior.

Recommended fix:
- Use one shared session bootstrap for guest pages and one for protected pages.

## Suggested Fix Order

1. Rotate secrets and remove real default credentials from source.
2. Remove default admin password path.
3. Convert password hashing to `password_hash` / `password_verify`.
4. Centralize session handling and hardened cookies.
5. Add CSRF to all user POST forms.
6. Rebuild wallet mutation code around database transactions and row locks.
7. Implement production result settlement for normal and starline markets.
8. Fix withdrawal eligibility and server-side limits.
9. Fix result display timing and overnight market windows.
10. Clean up duplicate auth code, error logging, and `__MACOSX` files.

## Verification Performed

- Enumerated PHP source files with `rg --files`.
- Searched for wallet mutations, auth/session logic, password hashing, result logic, deposits, withdrawals, and admin flows.
- Ran `php -l` across PHP files outside `__MACOSX`; no syntax errors were reported.
- After remediation, ran `php -l` across 73 PHP files; no syntax errors were reported.
- Rechecked critical/high signatures: no default admin password string, no misspelled bank-token key in PHP source, no remaining betting POST trust in `total_point`, and no weak `rand()`/`srand()` helper usage outside this report.
