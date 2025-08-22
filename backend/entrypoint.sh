#!/bin/bash

if [ ! -d "vendor" ]; then
    echo "⚙️  Installing composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

exec "$@"
