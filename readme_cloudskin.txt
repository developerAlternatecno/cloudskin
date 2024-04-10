In local enviromnent copy the .env.example into the root directory, next copy the DB data connection and copy into the docker info connection

The first step to launch this project is installing [Docker](https://www.docker.com/) in our system. Once is done we open a terminal in the root folder of the project that we previously cloned from this repository adn execute the following command:
    
    docker compose up --build [-d]

Once we have our docker containers running we execute the following commands in the root folder of our project:
    
    docker compose run --rm php composer install

    docker compose run --rm npm install

    docker compose run --rm nginx chmod 777 -R /var/www/html/storage

    docker compose run --rm nginx chmod 777 -R /var/www/html/bootstrap

In order to run artisan commands we need to copy the app/.env.example file to app/.env. Then we need to execute the following commands:

    docker compose run --rm php php artisan key:generate

    docker compose run --rm php php artisan migrate:fresh --seed

    docker compose run --rm php php artisan storage:link

    docker-compose run --rm php php artisan vendor:publish 

Next we need to execute the special commands for laravel:backpack in our php container:

    docker compose run --rm php composer require backpack/crud:"4.1.*"

    docker compose run --rm php composer require --dev backpack/generators

    docker compose run --rm php php artisan backpack:install

Passport commands, execute after having the repository pulled and ready to work:
    
    docker compose run --rm php php artisan passport:install
    docker compose run --rm php php artisan migrate:fresh --seed

CUANDO FALLA PASSPORT:
    
    docker compose run --rm php php artisan passport:install --force --uuids
    docker compose run --rm php php artisan db:seed

    - Comprobar si en la tabla oauth_access_tokens el user_id es string
