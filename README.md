# Laravel AngularJS Authentication

Includes Way Generators!

Required:

    Git
    NodeJS
    Ruby Compass for SCSS or build with Less
    PHP Composer

Install Backend:

    Download package
    $ git clone https://beesightsoft@bitbucket.org/beesightsoft/clinic-laravel-angular.git
    $ cd clinic-laravel-angular
    $ chmod -R 777 app/storage

    run composer
    $ composer install

    Create database in your local mysql server
    $ mysql -u root -p
    mysql > CREATE USER 'example'@'localhost' IDENTIFIED BY 'example';
    mysql > CREATE DATABASE IF NOT EXISTS `example`;
    mysql > GRANT ALL PRIVILEGES ON `example`.* TO 'example'@'localhost';

    or edit the database configuration file
    $ vi app/config/database.php

    run migration
    $ php artisan migrate

    run seeds
    $ php artisan db:seed

Install Node.js for development:

    $ npm install -g bower
    $ bower install
    $ npm install
    $ grunt

    build
    $ grunt build

- Setup your apache virtual host file. located: apache.conf or nginx.conf for nginx
Done