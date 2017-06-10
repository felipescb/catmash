## CatMash

[![Travis build](https://img.shields.io/travis/mathieutu/catmash/master.svg?style=flat-square&label=Build)](https://travis-ci.org/mathieutu/catmash?branch=master) 
[![Test coverage](https://img.shields.io/scrutinizer/coverage/g/mathieutu/catmash.svg?style=flat-square&label=Coverage)](https://scrutinizer-ci.com/g/mathieutu/catmash/?branch=master) 
[![Code quality](https://img.shields.io/scrutinizer/g/mathieutu/catmash.svg?style=flat-square&label=Quality)](https://scrutinizer-ci.com/g/mathieutu/catmash/?branch=master) 

CatMash is a facemash-like php project, to vote for the cutest cat ever! 

This readme is under redaction.

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

###License
The CatMash app is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
