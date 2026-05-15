# How to Run MainMatka Project Locally

## Requirements

- **XAMPP** (PHP 8.2 + MariaDB/MySQL) — installed at `C:\xampp`
- **Python 3.10+** — with pip
- **Google Chrome** browser (for scraper)

---

## Step 1: Install Python Dependencies

```bash
pip install requests beautifulsoup4 mysql-connector-python selenium webdriver-manager
```

---

## Step 2: Start MySQL

```bash
C:\xampp\mysql_start.bat
```

Wait 5 seconds for MySQL to be ready.

---

## Step 3: Create Database (First Time Only)

```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS mainmatka CHARACTER SET utf8mb4;"
```

Import the schema:

```bash
C:\xampp\mysql\bin\mysql.exe -u root mainmatka < database\init.sql
```

---

## Step 4: Start PHP Server

```bash
C:\xampp\php\php.exe -S localhost:8080 -t "MainMatka_Game"
```

Website will be live at: **http://localhost:8080/**

---

## Step 5: Start Market Scraper

Open a new terminal:

```bash
python scraper/market_scraper.py
```

This scrapes live market data from dpboss every 5 seconds and updates the database.

---

## Access URLs

| Page | URL |
|------|-----|
| Homepage | http://localhost:8080/ |
| Admin Panel | http://localhost:8080/cpanel_hgCBWj0S/ |
| Register | http://localhost:8080/register.php |
| Login | http://localhost:8080/login.php |

---

## Admin Login

| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## Project Structure

```
maTKA-KING-ID/
├── MainMatka_Game/          # PHP website (main project)
│   ├── index.php            # Homepage with all markets
│   ├── include/
│   │   ├── connect.php      # DB connection + core functions
│   │   ├── local-config.php # Local DB credentials
│   │   ├── scraped-markets.php        # Renders scraped markets on homepage
│   │   ├── scraped-market-game-setup.php  # Sets up vars for betting pages
│   │   └── scraped-bet-check.php      # Backend bet validation
│   ├── cpanel_hgCBWj0S/    # Admin panel (hidden path)
│   ├── single.php           # Single Ank betting
│   ├── jodi.php             # Jodi betting
│   ├── single-patti.php     # Single Patti betting
│   ├── double-patti.php     # Double Patti betting
│   ├── triple-patti.php     # Triple Patti betting
│   ├── half-sangam.php      # Half Sangam betting
│   ├── full-sangam.php      # Full Sangam betting
│   └── game-dashboard.php   # Market betting options page
├── scraper/
│   └── market_scraper.py    # Python scraper (Selenium + MySQL)
├── database/
│   └── init.sql             # Database schema + seed data
└── HOW-TO-RUN.md            # This file
```

---

## How It Works

1. **Scraper** fetches live market data (name, time, results) from dpboss-king.vercel.app every 5 seconds using headless Chrome
2. Data is stored in `scraped_markets` table with display order matching the source
3. **Homepage** shows all markets with live results — no "loading" text ever shown
4. **Betting** closes 10 minutes before result time (both frontend button disabled + backend validation)
5. Users can bet on Single Ank, Jodi, Single/Double/Triple Patti, Half/Full Sangam

---

## Notes

- Admin panel path is hidden: `cpanel_hgCBWj0S` (change this in production)
- To change admin password, generate hash: `C:\xampp\php\php.exe -r "echo password_hash('NEW_PASSWORD', PASSWORD_DEFAULT);"`
- Then update `MAINMATKA_ADMIN_PASSWORD_HASH` in `MainMatka_Game/include/local-config.php`
- Scraper reuses Chrome browser across cycles (no restart needed)
- Press `Ctrl+C` to stop the scraper gracefully
