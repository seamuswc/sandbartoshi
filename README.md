cp .env.example .env

composer install

php artisan key:generate

php artisan migrate

npm install

npm run build


__
Potential useful commands

php artisan images:list

rm -rf storage/app/public/property_images/*