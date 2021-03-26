## install Laravel
- curl -s "https://laravel.build/wish-list-app" | bash

- php artisan serve


- create database wishlist;

.env:
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=wishlist
DB_USERNAME=...
DB_PASSWORD=...

(config defaults to mysql)

To consume the api we will use guzzlehttp/guzzle (already installed)

php artisan make:controller SneakerController --resource


use bootstrap 4
composer require laravel/ui
php artisan ui bootstrap
npm install


new npm version
sudo npm install -g npm

sudo npm run dev

php artisan make:migration create_wishlist_table
