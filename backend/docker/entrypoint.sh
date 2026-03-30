#!/bin/sh
# backend/docker/entrypoint.sh
# Runs before the main CMD on every container start.

set -e

# Fix var/ permissions (Symfony cache & logs)
if [ -d /app/var ]; then
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /app/var 2>/dev/null \
        || chmod -R 777 /app/var
fi

# Run Doctrine migrations automatically (skips if nothing pending)
if [ "$APP_ENV" != "test" ]; then
    echo "⏳ Running Doctrine migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
fi

exec "$@"
