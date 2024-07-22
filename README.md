# Laravel Subscription
## Project Details

#### Prerequisites
1. PHP >= 8.2
2. MySQL
## Project Setup
Firstly, you need to clone git repo. (Run it in your terminal)
```bash
git clone https://github.com/meteoguzhan/laravel-subscription
```
You need to copy env file and rename it as .env
```bash
cd laravel-subscription && cp .env.example .env
```
After clone project, you need to install packages. (Make sure your system exists composer)
```bash
composer install
```
Open .env file, Give your updated details of MySQL connection configuration.
<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-subscription
DB_USERNAME=root
DB_PASSWORD=secret
</pre>
If you don't have a database, you can create one.
You can migrate.
```bash
php artisan migrate
```
You can test the project.
```bash
php artisan test
```
You can start the project. (Make sure your system exists php)
```bash
php artisan serve
```
