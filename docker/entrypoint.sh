#!/bin/sh
set -eu
cd /app

for directory in storage/app/private storage/app/public storage/framework/cache/data storage/framework/cache/locks storage/framework/sessions storage/framework/views storage/logs bootstrap/cache /var/workshop; do
    mkdir -p "$directory"
done

if [ ! -s /var/workshop/app-key ]; then
    php -r 'echo "base64:".base64_encode(random_bytes(32));' > /var/workshop/app-key
    chmod 600 /var/workshop/app-key
fi
export APP_KEY="$(cat /var/workshop/app-key)"

if [ ! -f vendor/autoload.php ]; then
    echo 'Preparing isolated Composer dependencies...'
    cp -a /opt/workshop/vendor/. vendor/
fi
# Safe to repeat: verifies the lock file and refreshes autoloading after branch changes.
composer install --prefer-dist --no-interaction --no-progress
workshop-artisan optimize:clear

workshop_new_database=false
if [ ! -f /var/workshop/database.sqlite ]; then
    touch /var/workshop/database.sqlite
    workshop_new_database=true
fi
workshop-artisan migrate --force --no-interaction
if [ "$workshop_new_database" = true ] || [ ! -f /var/workshop/seeded ]; then
    workshop-artisan db:seed --force --no-interaction
    touch /var/workshop/seeded
fi

exec "$@"
