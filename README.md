<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# How to use

### Before use, create a database in your local system using mysql and copy env.example file to .env for fill database name.

```shell
- git clone https://github.com/AdkPrtm/test-teknis-comtelindo.git
- cd test-teknis-comtelindo
- php artisan migrate:fresh
- php artisan db:seed --class=ProductSeeder
- php artisan key:generate 
- npm i
- npm run dev 
- php artisan serve
```

## To run test app open new terminal and type 
```shell
- php artisan test
```

### If you want try see the order page, please register your account first to get id and change the OrderSeeder id of user. After that run this command

```shell
php artisan db:seed --class=OrderSeeder
```

## NOTE:
- After `npm run dev` please do not close and open new terminal to running `php artisan serve`
- I can't be a Frontend Engginer website at all, so this web is just a functionality run through feature test.
- Run this application with internet connection
