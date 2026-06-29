# Sandbartoshi

Property map for **サンドバー投資株式会社**. One page — a Google Map with pins. No admin, no property pages.

Edit listings in **`data/properties.json`**, then restart the app.

---

## Add a property

Edit `data/properties.json`:

```json
[
  {
    "title": "東峰マンション薬院（1）",
    "size": 25,
    "lat": 33.58338166353338,
    "lng": 130.400754045393
  }
]
```

- **title** — name shown on the map popup
- **size** — square metres (shown as `sqm`)
- **lat / lng** — from Google Maps (right-click → copy coordinates)

Properties at the **same lat/lng share one pin**. The popup lists each unit at that address.

Restart after edits: `npm start` (or `systemctl restart sandbartoshi` on the server).

---

## Local setup

```bash
cd sandbartoshi
cp .env.example .env
npm install
npm start
```

Open **http://localhost:3000**

---

## Digital Ocean — server setup

### 1. Create a Droplet

- **Image:** Ubuntu 24.04
- **Size:** Basic $6/mo (1 GB RAM) is enough
- Add your SSH key

### 2. Point your domain (optional)

| Type | Name | Value |
|------|------|-------|
| A | `@` or `www` | Your Droplet IP |

### 3. SSH in

```bash
ssh root@YOUR_DROPLET_IP
```

### 4. Install Node.js

```bash
apt update && apt upgrade -y
apt install -y git nginx certbot python3-certbot-nginx
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

### 5. Clone the app

```bash
mkdir -p /var/www
cd /var/www
git clone YOUR_GIT_REPO_URL sandbartoshi
cd sandbartoshi
cp .env.example .env
npm install --omit=dev
```

### 6. Start with systemd

```bash
cp deploy/sandbartoshi.service /etc/systemd/system/
systemctl daemon-reload
systemctl enable sandbartoshi
systemctl start sandbartoshi
```

### 7. Nginx

```bash
cp deploy/nginx.conf.example /etc/nginx/sites-available/sandbartoshi
nano /etc/nginx/sites-available/sandbartoshi   # set your domain
ln -s /etc/nginx/sites-available/sandbartoshi /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

### 8. HTTPS

```bash
certbot --nginx -d your-domain.com
```

---

## Deploy updates

```bash
cd /var/www/sandbartoshi
git pull
npm ci --omit=dev
sudo systemctl restart sandbartoshi
```

---

## Troubleshooting

**Map is blank** — check browser console for Google Maps API errors.

**502 Bad Gateway** — `systemctl status sandbartoshi` and `journalctl -u sandbartoshi -n 50`.

**Pin missing** — check `lat`/`lng` in `data/properties.json` and restart the app.
