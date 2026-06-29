# Sandbartoshi

Map for サンドバー投資株式会社. Edit `data/properties.json`, restart app.

---

## Local

```bash
cd ~/Desktop/sandbartoshi
cp .env.example .env
npm install
npm start
```

http://localhost:3000

---

## Digital Ocean (run on the server after SSH)

```bash
apt update && apt upgrade -y
apt install -y git nginx
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

```bash
mkdir -p /var/www
cd /var/www
git clone https://github.com/seamuswc/sandbartoshi.git
cd sandbartoshi
cp .env.example .env
npm install --omit=dev
```

```bash
cp deploy/sandbartoshi.service /etc/systemd/system/
systemctl daemon-reload
systemctl enable sandbartoshi
systemctl start sandbartoshi
systemctl status sandbartoshi
```

```bash
cp deploy/nginx.conf.example /etc/nginx/sites-available/sandbartoshi
ln -sf /etc/nginx/sites-available/sandbartoshi /etc/nginx/sites-enabled/sandbartoshi
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx
```

In Namecheap: A record → Droplet IP. HTTPS via Namecheap (not certbot on this server).

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

Same lat/lng = one pin, multiple names in the popup.

```bash
systemctl restart sandbartoshi
```

---

## Deploy updates

```bash
cd /var/www/sandbartoshi
git pull origin main
npm ci --omit=dev
systemctl restart sandbartoshi
```

---

## If map is blank

Browser console → Google Maps errors.

```bash
systemctl status sandbartoshi
journalctl -u sandbartoshi -n 50
```
