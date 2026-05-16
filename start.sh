#!/bin/bash
# Ensure both PHP and Python see the same wall-clock day
export TZ="${TZ:-Asia/Kolkata}"

# Create log file before tailing to avoid race condition warning
touch /var/log/scraper.log

# Start the market scraper in background, redirect logs so Render shows them
python3 -u /opt/scraper/market_scraper.py >> /var/log/scraper.log 2>&1 &

# Tail scraper log to Apache stdout so it shows up in Render's log stream
tail -F /var/log/scraper.log &

# Start Apache in foreground
exec apache2-foreground
