#!/bin/sh
set -eu
# A separate generated key lives with Docker data, never in the host .env file.
export APP_KEY="$(cat /var/workshop/app-key)"
cd /app
exec php artisan "$@"
