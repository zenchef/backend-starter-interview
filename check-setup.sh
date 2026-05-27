#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

ok()   { echo -e "${GREEN}✓${NC} $1"; }
fail() { echo -e "${RED}✗${NC} $1"; exit 1; }

echo "Checking setup..."
echo ""

# PHP
command -v php >/dev/null 2>&1 || fail "PHP not found. Install PHP >= 8.2."
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
ok "PHP $PHP_VERSION"

# Composer
command -v composer >/dev/null 2>&1 || fail "Composer not found. See https://getcomposer.org"
ok "Composer $(composer --version --no-ansi 2>/dev/null | awk '{print $3}')"

# Vendor
[ -d vendor ] || fail "Dependencies not installed. Run: composer install"
ok "Dependencies installed"

# .env
[ -f .env ] || fail ".env file missing. Run: cp .env.example .env && php artisan key:generate"
ok ".env file present"

# Database
php artisan migrate:status --no-ansi >/dev/null 2>&1 || fail "Database not ready. Run: php artisan migrate --seed"
ok "Database ready"

# Dev server smoke test
php artisan serve --port=8000 &>/dev/null &
SERVER_PID=$!
sleep 2

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")
kill $SERVER_PID 2>/dev/null

if [ "$HTTP_STATUS" = "200" ]; then
    ok "Dev server responds at http://localhost:8000"
else
    fail "Dev server not responding (got HTTP $HTTP_STATUS). Try: php artisan serve"
fi

echo ""
echo "All checks passed. Run 'php artisan serve' and open http://localhost:8000"
