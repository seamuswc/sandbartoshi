#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/sandbartoshi}"
cd "$APP_DIR"

git pull origin main
npm ci --omit=dev
sudo systemctl restart sandbartoshi

echo "Deploy done."
