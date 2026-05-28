#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

ok()   { echo -e "${GREEN}✓${NC} $1"; }
fail() { echo -e "${RED}✗${NC} $1"; exit 1; }

echo "→ Copying .env.example to .env if missing"
if [ ! -f .env ]; then
  cp .env.example .env
  ok ".env created from .env.example"
else
  ok ".env already exists, skipping"
fi

echo "→ Installing Composer dependencies"
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --user $(id -u):$(id -g) \
  composer install
ok "Composer dependencies installed"

echo "→ Symlinking Sail binary"
ln -s ./vendor/bin/sail .
chmod +x sail
ok "Sail symlink created"
