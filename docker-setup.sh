#!/bin/bash

# Stop on the first sign of trouble
set -e

# Copy .env.example to .env in the current directory if .env doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Copy .env.example to .env in the app directory if .env doesn't exist
if [ ! -f app/.env ]; then
    cp app/.env.example app/.env
fi

# Build the Docker images
docker-compose up --build -d

# Install PHP dependencies
docker-compose run --rm php composer install

# Install NPM dependencies
docker-compose run --rm npm install

# Set permissions for storage and bootstrap
docker-compose run --rm nginx chmod 777 -R /var/www/html/storage
docker-compose run --rm nginx chmod 777 -R /var/www/html/bootstrap

# Configure the environment
docker-compose run --rm php php artisan key:generate
docker-compose run --rm php php artisan migrate:fresh --seed
docker-compose run --rm php php artisan storage:link
expect -c "
    spawn docker-compose run --rm php php artisan vendor:publish
    expect \"Which provider or tag's files would you like to publish?:\"
    send \"0\r\"
    expect eof
"

# Install Laravel Backpack
docker-compose run --rm php composer require backpack/crud:"4.1.*"
docker-compose run --rm php composer require --dev backpack/generators

# Install Laravel Passport
docker-compose run --rm php php artisan migrate:fresh --seed

echo "Setup completed successfully!"