#!/usr/bin/env bash
#
# Deploy / update the app on the server (run from the project root on the server).
#
# Usage:
#   chmod +x scripts/deploy.sh
#   ./scripts/deploy.sh
#
# Optional env overrides:
#   APP_DIR=/var/www/sandbartoshi GIT_BRANCH=main ./scripts/deploy.sh

set -euo pipefail

APP_DIR="${APP_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
GIT_BRANCH="${GIT_BRANCH:-main}"

PHP="${PHP:-php}"
COMPOSER="${COMPOSER:-composer}"

# Set SKIP_NPM=1 if the server does not run Node (build assets elsewhere and rsync public/build).
SKIP_NPM="${SKIP_NPM:-0}"

cd "$APP_DIR"

echo "==> Deploy directory: $APP_DIR"
echo "==> Branch: $GIT_BRANCH"

if [[ ! -f artisan ]]; then
  echo "error: artisan not found — run this from the Laravel project root." >&2
  exit 1
fi

echo "==> Git: fetch + pull"
git fetch origin
git checkout "$GIT_BRANCH"
git pull origin "$GIT_BRANCH"

echo "==> Composer: install (production)"
"$COMPOSER" install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [[ "$SKIP_NPM" != "1" ]]; then
  if command -v npm >/dev/null 2>&1; then
    echo "==> NPM: install + build"
    if [[ -f package-lock.json ]]; then
      npm ci
    else
      npm install
    fi
    npm run build
  else
    echo "warning: npm not found — skipping Vite build (set SKIP_NPM=1 to silence)." >&2
  fi
else
  echo "==> NPM: skipped (SKIP_NPM=1)"
fi

echo "==> Laravel: migrate"
"$PHP" artisan migrate --force

echo "==> Laravel: optimize"
"$PHP" artisan config:cache
"$PHP" artisan route:cache
"$PHP" artisan view:cache
"$PHP" artisan event:cache 2>/dev/null || true

if "$PHP" artisan list | grep -q 'queue:restart'; then
  echo "==> Laravel: queue restart"
  "$PHP" artisan queue:restart 2>/dev/null || true
fi

echo "==> Done."
