"""
MainMatka Market Scraper
Scrapes market data from dpboss-king.vercel.app every 5 seconds.
Stores market names, open/close times, and results into MySQL.
Handles "loading..." states by preserving last known result.
"""

import re
import time
import logging
import signal
import sys
from datetime import datetime

import mysql.connector
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ─── Configuration ───────────────────────────────────────────────────────────
SCRAPE_URL = "https://dpboss-king.vercel.app/"
SCRAPE_INTERVAL = 5  # seconds

DB_CONFIG = {
    "host": "127.0.0.1",
    "user": "root",
    "password": "",
    "database": "mainmatka",
    "port": 3306,
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
    "GOLDEN ANK",
    "FINAL ANK",
    "DPBOSS SPECIAL GAME ZONE",
    "MATKA JODI LIST",
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
            `open_time` VARCHAR(20) DEFAULT NULL,
            `close_time` VARCHAR(20) DEFAULT NULL,
            `open_panna` VARCHAR(10) DEFAULT NULL,
            `open_ank` VARCHAR(5) DEFAULT NULL,
            `close_panna` VARCHAR(10) DEFAULT NULL,
            `close_ank` VARCHAR(5) DEFAULT NULL,
            `jodi` VARCHAR(5) DEFAULT NULL,
            `full_result` VARCHAR(30) DEFAULT NULL,
            `result_status` VARCHAR(20) DEFAULT 'waiting',
            `last_updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `date` DATE NOT NULL,
            UNIQUE KEY `idx_market_date` (`market_slug`, `date`),
            INDEX `idx_date` (`date`),
            INDEX `idx_status` (`result_status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    """)
    conn.commit()
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

        service = Service(ChromeDriverManager().install())
        _driver = webdriver.Chrome(service=service, options=options)
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
def scrape_markets():
    """Scrape all market data from the source website using headless Chrome."""
    try:
        driver = get_driver()
        driver.get(SCRAPE_URL)

        # Wait for market cards to load (h4 elements appear)
        WebDriverWait(driver, 15).until(
            EC.presence_of_all_elements_located((By.TAG_NAME, "h4"))
        )
        # Extra wait for results to populate
        time.sleep(2)

        page_source = driver.page_source
    except Exception as e:
        log.error(f"Failed to fetch page: {e}")
        close_driver()
        return []

    from bs4 import BeautifulSoup
    soup = BeautifulSoup(page_source, "html.parser")
    markets = []

    # Each market is in an h4 heading followed by result and time info
    h4_tags = soup.find_all("h4")

    for h4 in h4_tags:
        market_name = h4.get_text(strip=True)

        # Clean market name - remove [main] suffix etc
        market_name_clean = re.sub(r'\s*\[.*?\]\s*', '', market_name).strip()

        # Skip non-market headings
        if not market_name_clean or market_name_clean.upper() in SKIP_MARKETS:
            continue
        if len(market_name_clean) < 2 or len(market_name_clean) > 80:
            continue

        # Get the parent container to find result and time
        parent = h4.find_parent()
        if not parent:
            continue

        # Get all text content from the parent block
        all_text = parent.get_text(separator="\n", strip=True)
        lines = [l.strip() for l in all_text.split("\n") if l.strip()]

        result_text = ""
        time_text = ""

        for line in lines:
            # Result pattern: digits-digits-digits or digits-digit
            if re.match(r'^\d{3}-\d{1,2}(-\d{3})?$', line):
                result_text = line
            # Time pattern: contains AM/PM with colon
            elif re.search(r'\d{1,2}:\d{2}\s*[AP]M', line, re.IGNORECASE):
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
            **result_data,
        })

    # Filter: only keep entries that have valid open/close times
    markets = [m for m in markets if m["open_time"] and m["close_time"]]

    return markets


# ─── Database Update ─────────────────────────────────────────────────────────
def update_database(markets):
    """Update scraped_markets table. Preserve last result if current is 'loading'."""
    if not markets:
        return 0

    today = datetime.now().strftime("%Y-%m-%d")
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    updated = 0

    for m in markets:
        slug = m["market_slug"]

        # Check existing record for today
        cursor.execute(
            "SELECT * FROM scraped_markets WHERE market_slug = %s AND date = %s LIMIT 1",
            (slug, today)
        )
        existing = cursor.fetchone()

        # If status is 'waiting' and we already have a result, don't overwrite
        if m["status"] == "waiting" and existing and existing["result_status"] in ("open_declared", "closed"):
            continue

        # If status is 'open_declared' and existing is 'closed', don't downgrade
        if m["status"] == "open_declared" and existing and existing["result_status"] == "closed":
            continue

        if existing:
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
                    last_updated = NOW()
                WHERE market_slug = %s AND date = %s
            """, (
                m["market_name"], m["display_order"], m["open_time"], m["close_time"],
                m["open_panna"], m["open_ank"], m["close_panna"],
                m["close_ank"], m["jodi"], m["full_result"],
                m["status"], slug, today
            ))
        else:
            # Insert new record
            cursor.execute("""
                INSERT INTO scraped_markets
                    (market_name, market_slug, display_order, open_time, close_time,
                     open_panna, open_ank, close_panna, close_ank,
                     jodi, full_result, result_status, date)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                m["market_name"], slug, m["display_order"], m["open_time"], m["close_time"],
                m["open_panna"], m["open_ank"], m["close_panna"],
                m["close_ank"], m["jodi"], m["full_result"],
                m["status"], today
            ))

        updated += 1

    conn.commit()
    cursor.close()
    conn.close()
    return updated


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
        elapsed = time.time() - start
        sleep_time = max(0, SCRAPE_INTERVAL - elapsed)
        time.sleep(sleep_time)

    log.info("Scraper stopped.")
    close_driver()


if __name__ == "__main__":
    main()
