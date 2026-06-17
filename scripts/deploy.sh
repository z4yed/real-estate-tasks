#!/usr/bin/env bash
# Deploy script — invoked on the VPS by the GitHub Actions deploy job.
# Run from the project directory:
#   cd ~/apps/<project> && bash scripts/deploy.sh

set -euo pipefail

cd "$(dirname "$0")/.."

if [[ ! -f .env ]]; then
    echo "Missing .env in $(pwd). Did you run project-setup.sh?" >&2
    exit 1
fi

# COMPOSE_PROJECT_NAME comes from .env so volume names are stable.
set -a
# shellcheck disable=SC1091
source .env
set +a

COMPOSE="docker compose -f docker-compose.prod.yml"

echo "==> Syncing config from git"
git fetch --depth=1 origin
git reset --hard "origin/$(git rev-parse --abbrev-ref HEAD)"

echo "==> Pulling image $APP_IMAGE"
$COMPOSE pull

echo "==> Recreating app/nginx with fresh code volume"
$COMPOSE stop app nginx
$COMPOSE rm -f app nginx
docker volume rm "${COMPOSE_PROJECT_NAME}_app-code" >/dev/null 2>&1 || true

echo "==> Starting stack"
$COMPOSE up -d

echo "==> Waiting for app container"
for _ in {1..30}; do
    if $COMPOSE exec -T app php -v >/dev/null 2>&1; then
        break
    fi
    sleep 2
done

echo "==> Running migrations + caches"
$COMPOSE exec -T app php artisan migrate --force
$COMPOSE exec -T app php artisan storage:link || true
$COMPOSE exec -T app php artisan optimize

echo "==> Pruning dangling images"
docker image prune -f >/dev/null

echo "==> Deployed."
