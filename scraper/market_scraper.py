"""
MainMatka Market Scraper
Scrapes market data from dpboss-king.vercel.app every 3 seconds.
Stores market names, open/close times, and results into MySQL.
Handles "loading..." states by preserving last known result.
"""

import os
import re
import time
import logging
import signal
import sys
from datetime import datetime, timezone, timedelta
from typing import Optional

import requests as http_requests
import mysql.connector
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Asia/Kolkata = UTC+5:30 (no DST), used to align "today" with PHP server timezone.
IST = timezone(timedelta(hours=5, minutes=30))

def today_ist() -> str:
    """Return today's date string in Asia/Kolkata timezone (matches PHP backend)."""
    return datetime.now(IST).strftime("%Y-%m-%d")

def now_ist_str() -> str:
    return datetime.now(IST).strftime("%Y-%m-%d %H:%M:%S")

# ─── Configuration ───────────────────────────────────────────────────────────
SCRAPE_URL = "https://dpboss-king.vercel.app/"
SCRAPE_INTERVAL = 3  # seconds between end of one cycle and start of next
MIN_CYCLE_DELAY = 1  # minimum delay (seconds) when a cycle exceeds SCRAPE_INTERVAL

# Settlement endpoint configuration
# Primary names match PHP-side (MAINMATKA_SETTLEMENT_*); legacy names kept as fallback.
SETTLEMENT_URL = (
    os.environ.get("MAINMATKA_SETTLEMENT_URL")
    or os.environ.get("SETTLEMENT_URL")
    or "http://localhost/cpanel_hgCBWj0S/settle-market.php"
)
SETTLEMENT_SECRET_KEY = (
    os.environ.get("MAINMATKA_SETTLEMENT_SECRET")
    or os.environ.get("SETTLEMENT_SECRET_KEY")
    or ""
)
SETTLEMENT_TIMEOUT = 10  # seconds

DB_CONFIG = {
    "host": os.environ.get("MAINMATKA_DB_HOST", "127.0.0.1"),
    "user": os.environ.get("MAINMATKA_DB_USER", "root"),
    "password": os.environ.get("MAINMATKA_DB_PASS", ""),
    "database": os.environ.get("MAINMATKA_DB_NAME", "mainmatka"),
    "port": int(os.environ.get("MAINMATKA_DB_PORT", "3306")),
}

# Markets to skip (not real markets)
SKIP_MARKETS = {
    "FOR MARKET ADD EMAIL US",
    "WORLD ME SABSE FAST SATTA MATKA RESULT",
    "LIVE RESULT",
    "☔LIVE RESULT☔",
    "MAIN STARLINE",
    "MUMBAI RAJSHREE STAR LINE RESULT",
    "MAIN BOMBAY 36 BAZAR",
    "MAIN FATA-FAT15 MINUTES",
    "MAIN FATA-FAT",
    "GOLDEN ANK",
    "FINAL ANK",
    "DPBOSS SPECIAL GAME ZONE",
    "MATKA JODI LIST",
    "TODAY LUCKY NUMBER",
    "SATTA MATKA JODI CHART",
    "MATKA PANEL CHART",
    "FREE GAME ZONE OPEN-CLOSE",
    "NOTICE",
    "☆ NOTICE ☆",
}

# ─── Logging ─────────────────────────────────────────────────────────────────
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
)
log = logging.getLogger("scraper")

# ─── Graceful shutdown ───────────────────────────────────────────────────────
running = True

def signal_handler(sig, frame):
    global running
    log.info("Shutdown signal received. Stopping...")
    running = False

signal.signal(signal.SIGINT, signal_handler)
signal.signal(signal.SIGTERM, signal_handler)


# ─── Database Setup ──────────────────────────────────────────────────────────
def get_db_connection():
    return mysql.connector.connect(**DB_CONFIG)


def ensure_table_exists():
    """Create the scraped_markets table if it doesn't exist."""
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS `scraped_markets` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `market_name` VARCHAR(100) NOT NULL,
            `market_slug` VARCHAR(100) NOT NULL,
            `display_order` INT NOT NULL DEFAULT 0,
            `open_time` VARCHAR(20) DEFAULT NULL,
            `close_time` VARCHAR(20) DEFAULT NULL,
            `open_panna` VARCHAR(10) DEFAULT NULL,
            `open_ank` VARCHAR(5) DEFAULT NULL,
            `close_panna` VARCHAR(10) DEFAULT NULL,
            `close_ank` VARCHAR(5) DEFAULT NULL,
            `jodi` VARCHAR(5) DEFAULT NULL,
            `full_result` VARCHAR(30) DEFAULT NULL,
            `result_status` VARCHAR(20) DEFAULT 'waiting',
            `is_live` TINYINT(1) NOT NULL DEFAULT 0,
            `last_updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `date` DATE NOT NULL,
            UNIQUE KEY `idx_market_date` (`market_slug`, `date`),
            INDEX `idx_date` (`date`),
            INDEX `idx_status` (`result_status`),
            INDEX `idx_is_live` (`is_live`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    """)
    conn.commit()
    # Add is_live column for legacy installs that already had the table
    try:
        cursor.execute("ALTER TABLE scraped_markets ADD COLUMN is_live TINYINT(1) NOT NULL DEFAULT 0")
        conn.commit()
        log.info("Added is_live column to scraped_markets.")
    except mysql.connector.Error as e:
        # 1060 = duplicate column, expected on subsequent runs
        if e.errno != 1060:
            log.warning(f"Could not add is_live column (may already exist): {e}")
    try:
        cursor.execute("CREATE INDEX idx_is_live ON scraped_markets (is_live)")
        conn.commit()
    except mysql.connector.Error:
        pass  # Index probably already exists
    cursor.close()
    conn.close()
    log.info("Table 'scraped_markets' ready.")


# ─── Result Parsing ──────────────────────────────────────────────────────────
def calculate_ank(panna):
    """Calculate ank (last digit of sum of panna digits)."""
    if not panna or not panna.isdigit():
        return ""
    total = sum(int(d) for d in panna)
    return str(total % 10)


def parse_result(result_text):
    """
    Parse result string into components.
    Formats:
      - "289-97-278"  -> full result (open_panna-jodi-close_panna)
      - "345-2"       -> partial (only open declared: open_panna-open_ank)
      - "***-**-***"  -> no result yet
      - ""            -> loading/empty
    Returns dict with: open_panna, open_ank, close_panna, close_ank, jodi, status
    """
    result = {
        "open_panna": "",
        "open_ank": "",
        "close_panna": "",
        "close_ank": "",
        "jodi": "",
        "full_result": result_text.strip() if result_text else "",
        "status": "waiting",
    }

    if not result_text:
        return result

    text = result_text.strip()

    # Skip loading/placeholder states - never store these as results
    if "loading" in text.lower() or "refresh" in text.lower() or text in ("", "***-**-***", "***-*-***", "***-*", "**-***"):
        result["status"] = "waiting"
        return result

    # Full result: "289-97-278" (open_panna - jodi - close_panna)
    full_match = re.match(r'^(\d{3})-(\d{2})-(\d{3})$', text)
    if full_match:
        result["open_panna"] = full_match.group(1)
        result["open_ank"] = calculate_ank(full_match.group(1))
        result["jodi"] = full_match.group(2)
        result["close_panna"] = full_match.group(3)
        result["close_ank"] = calculate_ank(full_match.group(3))
        result["status"] = "closed"
        return result

    # Partial result: "345-2" (open_panna - open_ank, close not yet)
    partial_match = re.match(r'^(\d{3})-(\d)$', text)
    if partial_match:
        result["open_panna"] = partial_match.group(1)
        result["open_ank"] = partial_match.group(2)
        result["status"] = "open_declared"
        return result

    # Only stars with partial: "***-*-***" or similar
    if "*" in text:
        result["status"] = "waiting"
        return result

    result["status"] = "unknown"
    return result


def parse_time(time_text):
    """Parse time string like '11:40 AM    12:40 PM' into (open_time, close_time)."""
    if not time_text:
        return ("", "")

    # Clean up whitespace
    text = re.sub(r'\s+', ' ', time_text.strip())

    # Match pattern: "HH:MM AM/PM HH:MM AM/PM"
    match = re.findall(r'(\d{1,2}:\d{2}\s*[AP]M)', text, re.IGNORECASE)
    if len(match) >= 2:
        return (match[0].strip(), match[1].strip())
    elif len(match) == 1:
        return (match[0].strip(), "")

    return ("", "")


# ─── Browser Setup ────────────────────────────────────────────────────────────
_driver = None

def get_driver():
    """Get or create a headless Chrome driver (reused across cycles)."""
    global _driver
    if _driver is None:
        options = Options()
        options.add_argument("--headless=new")
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--disable-gpu")
        options.add_argument("--window-size=1920,1080")
        options.add_argument("--disable-extensions")
        options.add_argument("--disable-images")
        options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36")

        # Selenium 4.6+ uses Selenium Manager which auto-resolves the
        # correct chromedriver for the installed Chrome browser.
        # We deliberately do NOT use webdriver-manager here because its
        # legacy endpoint caps at chromedriver 114 and breaks for Chrome 115+.
        try:
            _driver = webdriver.Chrome(options=options)
        except Exception as e:
            log.error(f"Selenium Manager failed to start Chrome: {e}")
            raise

        _driver.set_page_load_timeout(30)
        log.info("Chrome headless browser initialized.")
    return _driver


def close_driver():
    global _driver
    if _driver:
        try:
            _driver.quit()
        except:
            pass
        _driver = None


# ─── Scraping ────────────────────────────────────────────────────────────────
def _extract_live_market_slugs(soup):
    """
    Find the LIVE RESULT section on the source page and extract the slugs
    of the markets currently being broadcast there.

    The source page may render the LIVE block in two ways:
      A) Each live market as a separate <h4> heading (old layout)
      B) All live markets as text/paragraphs within a container (new layout)

    Returns a set of normalized slugs (matching scraped_markets.market_slug).
    """
    live_slugs = set()

    # Find the LIVE RESULT heading
    live_heading = None
    for h in soup.find_all(["h2", "h3", "h4", "h5"]):
        txt = h.get_text(strip=True)
        if "LIVE RESULT" in txt.upper():
            live_heading = h
            break

    if live_heading is None:
        return live_slugs

    # End markers — when we hit one of these, the live block is over.
    # These are headings that appear right after the LIVE block on the
    # source page. We compare in upper case, ignoring spaces/punctuation.
    end_marker_keywords = (
        "WORLD ME SABSE FAST",
        "DPBOSS SPECIAL",
        "MAIN STARLINE",
        "MUMBAI RAJSHREE STAR LINE",
        "MAIN BOMBAY 36 BAZAR",
        "MAIN FATA-FAT",
        "GOLDEN ANK",
        "FINAL ANK",
        "MATKA JODI LIST",
    )

    # Strategy A: Walk forward through heading elements (old layout)
    for el in live_heading.find_all_next(["h2", "h3", "h4", "h5"]):
        txt = el.get_text(strip=True)
        if not txt:
            continue
        upper = txt.upper()
        if any(k in upper for k in end_marker_keywords):
            break
        if "LIVE RESULT" in upper:
            continue
        if "SABSE TEZZ" in upper or "YAHI MILEGA" in upper:
            continue
        name_clean = re.sub(r'\s*\[.*?\]\s*', '', txt).strip()
        if not name_clean:
            continue
        if len(name_clean) < 2 or len(name_clean) > 60:
            continue
        if name_clean.upper() in SKIP_MARKETS:
            continue
        slug = re.sub(r'[^a-z0-9]+', '-', name_clean.lower()).strip('-')
        if slug:
            live_slugs.add(slug)

    # Strategy B: If no headings found, look for market names in text nodes
    # between the LIVE heading and the end marker. The source may render
    # live markets as text like "SRIDEVI NIGHT188-7" or "MUMBAI BAZARLoading..."
    if not live_slugs:
        for el in live_heading.find_all_next():
            if el.name in ("h2", "h3", "h4", "h5"):
                txt = el.get_text(strip=True).upper()
                if any(k in txt for k in end_marker_keywords):
                    break
                if "LIVE RESULT" not in txt:
                    # This heading IS a live market name (Strategy A already handled)
                    continue
            # Look for text that contains a market name followed by a result
            if el.string:
                text = el.string.strip()
            elif el.name in ("p", "span", "div", "button", "a"):
                text = el.get_text(strip=True)
            else:
                continue
            if not text or len(text) < 4:
                continue
            # Pattern: "MARKET NAMEresult" or "MARKET NAMELoading..."
            # Extract market name by removing trailing result/loading/refresh
            name_candidate = re.sub(r'(\d{3}-\d{1,2}(-\d{3})?|Loading\.{0,3}|Refresh).*$', '', text).strip()
            if name_candidate and len(name_candidate) >= 3 and len(name_candidate) <= 60:
                upper_name = name_candidate.upper()
                if upper_name in SKIP_MARKETS:
                    continue
                if "SABSE TEZZ" in upper_name or "YAHI MILEGA" in upper_name:
                    continue
                if "REFRESH" in upper_name or "LOADING" in upper_name:
                    continue
                # Only consider if it looks like a market name (mostly letters/spaces)
                if re.match(r'^[A-Za-z\s\-\.]+$', name_candidate):
                    slug = re.sub(r'[^a-z0-9]+', '-', name_candidate.lower()).strip('-')
                    if slug and len(slug) >= 3:
                        live_slugs.add(slug)

    return live_slugs


def scrape_markets():
    """Scrape all market data from the source website using headless Chrome."""
    try:
        driver = get_driver()
        driver.get(SCRAPE_URL)

        # Wait for market cards to load (h4 elements appear)
        WebDriverWait(driver, 20).until(
            EC.presence_of_all_elements_located((By.TAG_NAME, "h4"))
        )
        # Extra wait for results to populate (React hydration + API calls)
        time.sleep(4)

        page_source = driver.page_source
    except Exception as e:
        log.error(f"Failed to fetch page: {e}")
        close_driver()
        return []

    from bs4 import BeautifulSoup
    soup = BeautifulSoup(page_source, "html.parser")
    markets = []

    # ─── First pass: detect markets currently in LIVE RESULT section ────
    # When a market appears in this block, the source site is actively
    # broadcasting its result-declaration window — betting must be CLOSED.
    # When the market disappears from this block, betting reopens (subject
    # to the normal session/result_status rules).
    live_market_slugs = _extract_live_market_slugs(soup)
    if live_market_slugs:
        log.info(f"LIVE RESULT block: {len(live_market_slugs)} market(s) currently live: {sorted(live_market_slugs)}")

    # Each market is in an h4 heading followed by result and time info
    h4_tags = soup.find_all("h4")
    log.info(f"Found {len(h4_tags)} h4 tags on page")

    for h4 in h4_tags:
        market_name = h4.get_text(strip=True)

        # Clean market name - remove [main] suffix etc
        market_name_clean = re.sub(r'\s*\[.*?\]\s*', '', market_name).strip()

        # Skip non-market headings
        if not market_name_clean or market_name_clean.upper() in SKIP_MARKETS:
            continue
        if len(market_name_clean) < 2 or len(market_name_clean) > 80:
            continue

        # ─── Strategy: collect text from next siblings until the next heading.
        # The source page renders each market as:
        #   <h4>MARKET NAME</h4>
        #   <p>result</p>  or text node
        #   <p>time</p>    or text node
        #   <a>Jodi</a><a>Panel</a>
        # We use find_next_siblings + find_all_next to handle both flat and
        # nested DOM layouts.

        result_text = ""
        time_text = ""

        # Collect text lines between this h4 and the next heading
        collected_lines = []
        for el in h4.find_all_next():
            # Stop at the next heading element (next market or section)
            if el.name in ("h2", "h3", "h4", "h5", "h6"):
                break
            # Get direct text of this element (avoid duplicates from children)
            if el.string:
                text = el.string.strip()
                if text and text not in collected_lines:
                    collected_lines.append(text)
            elif el.name in ("p", "span", "div", "td") and not el.find(["p", "span", "div"]):
                # Leaf container — get its full text
                text = el.get_text(strip=True)
                if text and text not in collected_lines:
                    collected_lines.append(text)
            # Safety limit
            if len(collected_lines) > 30:
                break

        for line in collected_lines:
            # Skip link text like "Jodi", "Panel", "Chart", "Refresh"
            if line.lower() in ("jodi", "panel", "chart", "refresh"):
                continue
            # Result pattern: digits-digits-digits or digits-digit
            if not result_text and re.match(r'^\d{3}-\d{1,2}(-\d{3})?$', line):
                result_text = line
            # Time pattern: two times with AM/PM (e.g. "11:15 AM 12:15 PM")
            elif not time_text and re.search(r'\d{1,2}:\d{2}\s*[AP]M', line, re.IGNORECASE):
                if "AM" in line.upper() or "PM" in line.upper():
                    time_text = line

        # Fallback: try using next sibling elements directly
        if not result_text or not time_text:
            for sib in h4.next_siblings:
                if hasattr(sib, 'name') and sib.name in ("h2", "h3", "h4", "h5", "h6"):
                    break
                text = sib.get_text(strip=True) if hasattr(sib, 'get_text') else str(sib).strip()
                if not text:
                    continue
                if not result_text and re.match(r'^\d{3}-\d{1,2}(-\d{3})?$', text):
                    result_text = text
                elif not time_text and re.search(r'\d{1,2}:\d{2}\s*[AP]M', text, re.IGNORECASE):
                    time_text = text

        # Fallback 2: parent container (original approach, for wrapped layouts)
        if not result_text or not time_text:
            parent = h4.find_parent()
            if parent and parent.name not in ("body", "html", "[document]"):
                all_text = parent.get_text(separator="\n", strip=True)
                lines = [l.strip() for l in all_text.split("\n") if l.strip()]
                for line in lines:
                    if not result_text and re.match(r'^\d{3}-\d{1,2}(-\d{3})?$', line):
                        result_text = line
                    elif not time_text and re.search(r'\d{1,2}:\d{2}\s*[AP]M', line, re.IGNORECASE):
                        if "AM" in line.upper() or "PM" in line.upper():
                            time_text = line

        open_time, close_time = parse_time(time_text)
        result_data = parse_result(result_text)

        # Generate slug
        slug = re.sub(r'[^a-z0-9]+', '-', market_name_clean.lower()).strip('-')

        markets.append({
            "market_name": market_name_clean,
            "market_slug": slug,
            "display_order": len(markets) + 1,
            "open_time": open_time,
            "close_time": close_time,
            "is_live": slug in live_market_slugs,
            **result_data,
        })

    # Filter: only keep entries that have valid open/close times
    pre_filter_count = len(markets)
    markets = [m for m in markets if m["open_time"] and m["close_time"]]
    if pre_filter_count != len(markets):
        log.info(f"Filtered {pre_filter_count - len(markets)} markets without valid times (kept {len(markets)})")

    return markets


# ─── Database Update ─────────────────────────────────────────────────────────
def update_database(markets):
    """
    Update scraped_markets table with transition detection and settlement triggering.

    For each market:
    1. Detect status transition BEFORE the DB update (so old status is still available)
    2. Update the database with new market data
    3. If a valid transition was detected, trigger settlement AFTER the DB update
       (so the settlement engine reads fresh result data)

    Preserve last result if current is 'loading'.
    """
    if not markets:
        return 0

    today = today_ist()
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    updated = 0

    # Collect transitions to trigger settlement AFTER each market's DB update
    settlements_to_trigger = []

    for m in markets:
        slug = m["market_slug"]
        new_status = m["status"]

        # Check existing record for today
        cursor.execute(
            "SELECT * FROM scraped_markets WHERE market_slug = %s AND date = %s LIMIT 1",
            (slug, today)
        )
        existing = cursor.fetchone()

        # Detect "fresh-day overwrite": when source shows different open_panna
        # than what's stored, treat it as new day's data and ALWAYS overwrite —
        # even if that would normally look like a "downgrade" of status.
        # This fixes the stale-result bug where yesterday's closed result
        # (e.g. "170-83-256") sticks around when source shows today's
        # partial result (e.g. "245-1*-***").
        is_fresh_day_overwrite = False
        if existing:
            existing_open_panna = (existing.get("open_panna") or "").strip()
            new_open_panna = (m.get("open_panna") or "").strip()
            existing_full = (existing.get("full_result") or "").strip()
            new_full = (m.get("full_result") or "").strip()

            # Case A: stored closed (full result) but source now shows ONLY
            # an open result whose open_panna doesn't match stored open_panna.
            # That means the source has rolled into a new day's cycle.
            if (
                existing.get("result_status") == "closed"
                and new_status == "open_declared"
                and new_open_panna != ""
                and new_open_panna != existing_open_panna
            ):
                is_fresh_day_overwrite = True

            # Case B: stored closed/open_declared but source now shows ***-**-***
            # (waiting) AND the previously stored full_result doesn't match the
            # source's current display — the source has reset for a new day.
            elif (
                existing.get("result_status") in ("closed", "open_declared")
                and new_status == "waiting"
                and new_full == ""
            ):
                # Don't overwrite to waiting unconditionally — only when the
                # last_updated timestamp is from a prior calendar day.
                # `today` already filters by date column, so any record we
                # find here is for `today`. The reset case is handled by
                # the unique key on (market_slug, date) — yesterday's row
                # will simply not be returned, and we'll INSERT a new one.
                # So no override needed here.
                pass

        # If status is 'waiting' and we already have a result, don't overwrite —
        # UNLESS we detected a fresh-day overwrite above.
        skip_result_write = False
        if (
            new_status == "waiting"
            and existing
            and existing["result_status"] in ("open_declared", "closed")
            and not is_fresh_day_overwrite
        ):
            skip_result_write = True

        # If status is 'open_declared' and existing is 'closed', don't downgrade —
        # UNLESS we detected a fresh-day overwrite (different open_panna).
        if (
            new_status == "open_declared"
            and existing
            and existing["result_status"] == "closed"
            and not is_fresh_day_overwrite
        ):
            skip_result_write = True

        # Even when we skip the result-status write, the live flag and timing
        # info still need to track the source. Update those in isolation.
        if skip_result_write:
            new_is_live = 1 if m.get("is_live") else 0
            existing_is_live = int(existing.get("is_live") or 0) if existing else 0
            if new_is_live != existing_is_live:
                cursor.execute(
                    "UPDATE scraped_markets SET is_live = %s, last_updated = NOW() "
                    "WHERE market_slug = %s AND date = %s",
                    (new_is_live, slug, today)
                )
                updated += 1
                if new_is_live:
                    log.info(f"LIVE FLAG ON: '{m['market_name']}' [{slug}] entered LIVE RESULT block — betting closed.")
                else:
                    log.info(f"LIVE FLAG OFF: '{m['market_name']}' [{slug}] left LIVE RESULT block — betting may resume.")
            continue

        # ─── Step 1: Detect status transition BEFORE the DB update ────────
        # detect_status_transition() queries the DB for the OLD status,
        # so it must be called before we write the new data.
        old_status = detect_status_transition(slug, new_status, today)

        # ─── Step 2: Update the database with new market data ─────────────
        new_is_live = 1 if m.get("is_live") else 0
        if existing:
            # Track live-flag transitions for visibility
            existing_is_live = int(existing.get("is_live") or 0)
            if new_is_live != existing_is_live:
                if new_is_live:
                    log.info(f"LIVE FLAG ON: '{m['market_name']}' [{slug}] entered LIVE RESULT block — betting closed.")
                else:
                    log.info(f"LIVE FLAG OFF: '{m['market_name']}' [{slug}] left LIVE RESULT block — betting may resume.")
            # Update existing record
            cursor.execute("""
                UPDATE scraped_markets SET
                    market_name = %s,
                    display_order = %s,
                    open_time = %s,
                    close_time = %s,
                    open_panna = %s,
                    open_ank = %s,
                    close_panna = %s,
                    close_ank = %s,
                    jodi = %s,
                    full_result = %s,
                    result_status = %s,
                    is_live = %s,
                    last_updated = NOW()
                WHERE market_slug = %s AND date = %s
            """, (
                m["market_name"], m["display_order"], m["open_time"], m["close_time"],
                m["open_panna"], m["open_ank"], m["close_panna"],
                m["close_ank"], m["jodi"], m["full_result"],
                new_status, new_is_live, slug, today
            ))
        else:
            if new_is_live:
                log.info(f"LIVE FLAG ON: '{m['market_name']}' [{slug}] (new record) — betting closed.")
            # Insert new record
            cursor.execute("""
                INSERT INTO scraped_markets
                    (market_name, market_slug, display_order, open_time, close_time,
                     open_panna, open_ank, close_panna, close_ank,
                     jodi, full_result, result_status, is_live, date)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                m["market_name"], slug, m["display_order"], m["open_time"], m["close_time"],
                m["open_panna"], m["open_ank"], m["close_panna"],
                m["close_ank"], m["jodi"], m["full_result"],
                new_status, new_is_live, today
            ))

        updated += 1

        # ─── Step 3: Queue settlement trigger if transition detected ──────
        # Settlement is triggered AFTER the DB commit so the settlement
        # engine reads fresh result data from scraped_markets.
        if old_status is not None:
            # Get the market_id (scraped_markets.id) needed for settlement
            market_id = existing["id"] if existing else None
            if market_id is None:
                # For newly inserted records, fetch the ID
                cursor.execute(
                    "SELECT id FROM scraped_markets WHERE market_slug = %s AND date = %s LIMIT 1",
                    (slug, today)
                )
                row = cursor.fetchone()
                market_id = row["id"] if row else None

            if market_id:
                settlements_to_trigger.append({
                    "market_id": market_id,
                    "market_name": m["market_name"],
                    "old_status": old_status,
                    "new_status": new_status,
                })

    # Commit all DB updates first
    conn.commit()
    cursor.close()
    conn.close()

    # ─── Step 4: Trigger settlements AFTER DB commit ──────────────────────
    # This ensures the settlement engine reads fresh data from scraped_markets.
    for s in settlements_to_trigger:
        if s["old_status"] == "waiting" and s["new_status"] == "open_declared":
            trigger_settlement(s["market_id"], "open", s["market_name"])
        elif s["old_status"] == "open_declared" and s["new_status"] == "closed":
            trigger_settlement(s["market_id"], "close", s["market_name"])

    return updated


# ─── Status Transition Detection ─────────────────────────────────────────────
def detect_status_transition(market_slug: str, new_status: str, today: str) -> Optional[str]:
    """
    Compare new_status against stored result_status for market_slug on today.
    Returns the old_status if a transition occurred, None otherwise.

    When no change is detected, the transition flag is explicitly set to false
    (returning None signals no transition). Only logs when an actual status
    change is detected (previous status differs from current).
    """
    transition_detected = False
    old_status = None

    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # Query the current stored result_status before updating
        cursor.execute(
            "SELECT result_status, market_name FROM scraped_markets WHERE market_slug = %s AND date = %s LIMIT 1",
            (market_slug, today)
        )
        existing = cursor.fetchone()

        cursor.close()
        conn.close()

        if existing is None:
            # No existing record — this is a new insert, not a transition
            transition_detected = False
            return None

        stored_status = existing["result_status"]
        market_name = existing["market_name"]

        if stored_status != new_status:
            # Actual status change detected
            transition_detected = True
            old_status = stored_status

            # Log the transition with market name, previous status, new status, and timestamp
            log.info(
                f"STATUS TRANSITION: '{market_name}' [{market_slug}] "
                f"changed from '{old_status}' to '{new_status}' "
                f"at {now_ist_str()}"
            )
        else:
            # No change detected — explicitly set transition flag to false
            transition_detected = False

    except mysql.connector.Error as e:
        log.error(f"Database error in detect_status_transition for '{market_slug}': {e}")
        transition_detected = False

    if transition_detected:
        return old_status
    return None


# ─── Settlement Trigger ──────────────────────────────────────────────────────
def trigger_settlement(market_id: int, settlement_type: str, market_name: str) -> bool:
    """
    Call PHP settlement endpoint.
    settlement_type: 'open' or 'close'
    Returns True if settlement was triggered successfully.
    """
    if not SETTLEMENT_URL:
        log.error(f"SETTLEMENT_URL not configured. Cannot trigger {settlement_type} settlement for '{market_name}'.")
        return False

    payload = {
        "market_id": market_id,
        "settlement_type": settlement_type,
        "secret_key": SETTLEMENT_SECRET_KEY,
    }

    try:
        response = http_requests.post(
            SETTLEMENT_URL,
            data=payload,
            timeout=SETTLEMENT_TIMEOUT,
        )

        if response.status_code == 200:
            log.info(
                f"SETTLEMENT TRIGGERED: '{market_name}' (id={market_id}) "
                f"type='{settlement_type}' — response: {response.text[:200]}"
            )
            return True
        else:
            log.error(
                f"SETTLEMENT FAILED: '{market_name}' (id={market_id}) "
                f"type='{settlement_type}' — HTTP {response.status_code}: {response.text[:200]}"
            )
            return False

    except http_requests.exceptions.Timeout:
        log.error(
            f"SETTLEMENT TIMEOUT: '{market_name}' (id={market_id}) "
            f"type='{settlement_type}' — request timed out after {SETTLEMENT_TIMEOUT}s"
        )
        return False
    except http_requests.exceptions.ConnectionError:
        log.error(
            f"SETTLEMENT CONNECTION ERROR: '{market_name}' (id={market_id}) "
            f"type='{settlement_type}' — could not connect to {SETTLEMENT_URL}"
        )
        return False
    except http_requests.exceptions.RequestException as e:
        log.error(
            f"SETTLEMENT ERROR: '{market_name}' (id={market_id}) "
            f"type='{settlement_type}' — {e}"
        )
        return False


# ─── Main Loop ───────────────────────────────────────────────────────────────
def main():
    log.info("=" * 60)
    log.info("MainMatka Market Scraper Starting")
    log.info(f"Source: {SCRAPE_URL}")
    log.info(f"Interval: {SCRAPE_INTERVAL}s")
    log.info("=" * 60)

    ensure_table_exists()

    cycle = 0
    while running:
        cycle += 1
        start = time.time()

        try:
            markets = scrape_markets()
            if markets:
                updated = update_database(markets)
                log.info(f"Cycle #{cycle}: Scraped {len(markets)} markets, updated {updated} records")
            else:
                log.warning(f"Cycle #{cycle}: No markets found (site may be down)")
        except Exception as e:
            log.error(f"Cycle #{cycle}: Error - {e}")

        # Wait for next cycle
        # Requirement 1.1: Wait full SCRAPE_INTERVAL (3s) between end of cycle and start of next
        # Requirement 1.2: If cycle took longer than SCRAPE_INTERVAL, wait minimum MIN_CYCLE_DELAY (1s)
        elapsed = time.time() - start
        if elapsed > SCRAPE_INTERVAL:
            sleep_time = MIN_CYCLE_DELAY
        else:
            sleep_time = SCRAPE_INTERVAL
        time.sleep(sleep_time)

    log.info("Scraper stopped.")
    close_driver()


if __name__ == "__main__":
    main()
