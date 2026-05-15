# Deploy MainMatka on Render

## What You Need
- Render account (https://render.com)
- MySQL database (use Aiven.io free tier or PlanetScale)
- GitHub repo connected

---

## Step 1: Create Free MySQL Database

Go to **https://aiven.io** (free tier) or **https://planetscale.com** (free tier):

1. Sign up → Create new MySQL database
2. Note down: Host, Username, Password, Database name, Port
3. Import schema: Connect via MySQL client and run `database/init.sql`

Example with Aiven:
```
mysql -h your-host.aivencloud.com -P 12345 -u avnadmin -p your_db < database/init.sql
```

---

## Step 2: Deploy on Render

1. Go to **https://dashboard.render.com**
2. Click **New → Web Service**
3. Connect your GitHub repo: `Malaviya24/Matkaking-ID`
4. Settings:
   - **Name:** mainmatka-app
   - **Region:** Singapore (closest to India)
   - **Runtime:** Docker
   - **Dockerfile Path:** `./Dockerfile`
   - **Plan:** Free (or Starter $7/month for always-on)

5. Click **Advanced → Add Environment Variables:**

| Key | Value |
|-----|-------|
| `MAINMATKA_DB_HOST` | your-mysql-host.com |
| `MAINMATKA_DB_USER` | your_db_user |
| `MAINMATKA_DB_PASS` | your_db_password |
| `MAINMATKA_DB_NAME` | mainmatka |
| `MAINMATKA_SITE_URL` | https://your-app.onrender.com/ |
| `MAINMATKA_SITE_DOMAIN` | your-app.onrender.com |
| `MAINMATKA_ADMIN_USERNAME` | admin |
| `MAINMATKA_ADMIN_PASSWORD_HASH` | (generate with: php -r "echo password_hash('yourpass', PASSWORD_DEFAULT);") |
| `TZ` | Asia/Kolkata |

6. Click **Create Web Service**

---

## Step 3: Wait for Build

Render will:
- Build the Docker image (5-10 minutes first time)
- Start Apache + Scraper
- Give you a URL like `https://mainmatka-app.onrender.com`

---

## Step 4: Verify

1. Open `https://your-app.onrender.com` → Homepage with markets
2. Open `https://your-app.onrender.com/cpanel_hgCBWj0S/` → Admin login
3. Register a user and test betting

---

## Custom Domain (Optional)

1. In Render dashboard → your service → Settings → Custom Domain
2. Add your domain (e.g., mainmatka.app)
3. Update DNS: CNAME record pointing to `your-app.onrender.com`
4. Update `MAINMATKA_SITE_URL` and `MAINMATKA_SITE_DOMAIN` env vars

---

## Important Notes

- **Free tier** on Render sleeps after 15 min inactivity (first request takes 30s to wake up)
- **Starter plan** ($7/month) keeps it always running
- **Scraper** runs inside the same container — if container sleeps, scraper stops too
- **Database** is external (Aiven/PlanetScale) — always available regardless of Render sleep

---

## Updating Code

Just push to GitHub main branch — Render auto-deploys:
```bash
git add -A
git commit -m "your changes"
git push origin main
```
Render detects the push and rebuilds automatically.
