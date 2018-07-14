## SodexoMash

SodexoMash is a facemash-like php project, to vote for the best sodexo food ever! 

This readme is under redaction and this is a fork from mathieutu/catmash. 

### Hosting quickly
You can host this app with docker:
```docker
docker run --name catmash_db -e MYSQL_ALLOW_EMPTY_PASSWORD=yes -e MYSQL_DATABASE=catmash -d mysql
docker run --name catmash --link catmash_db:mysql -e API_URL=YOUR_API_URL -p 80 -d mathieutu/catmash
```
### Locally
#### Requirement
- PHP >= 7.1
- Composer
- Yarn
- MySQL/MariaDB

#### Setup
```bash
git clone git@github.com:mathieutu/catmash.git
composer install

cp .env.example .env
php artisan key:generate

# Modify .env for your database
php artisan migrate

yarn install
yarn dev

php artisan serve
```

See [https://laravel.com/docs/](https://laravel.com/docs/) for more information.

