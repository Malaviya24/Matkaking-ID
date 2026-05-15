#!/bin/bash
# Start the market scraper in background
python3 /opt/scraper/market_scraper.py &

# Start Apache in foreground
apache2-foreground
