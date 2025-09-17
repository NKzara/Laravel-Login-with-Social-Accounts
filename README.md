<p align="center"><a href="https://sltc.ac.lk/" target="_blank"><img src="https://lms.sltc.ac.lk/pluginfile.php/1/theme_moove/logo/1720331546/SLTC%20LOGO%202022%20500px%20X%20500px%20%281%29.png" width="400" alt="SLTC Logo"></a></p>

## About 'CCS3310 Software Engineering Methods' unit

Aim is to apply software engineering principles and practices for designing and testing quality software and for scientific and business applications, adapt to emerging information and communication technologies to innovate ideas to solve societal problems and analyze the real world problem to get a broader perspective of the discipline through research.

This repo has following features:

- Basic Laravel installation with Breeze (Blade) for UI flow
- Book manager (migration, model, factory & CRUD controller)
- Author manager (migration, model, factory & CRUD controller)

[Laravel v11](https://laravel.com) is used since it is an accessible, powerful, and provides tools required for small to large, robust applications.



## Code repository
Made with
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" height="75" alt="Laravel Logo"></a></p>

You may clone it from the [BitBucket public repo](https://bitbucket.org/az-sltc/ccs3310-2024.git).

## Setup instructions
Your PC must have [Composer](https://getcomposer.org/download/), [NPM](https://nodejs.org/en/download/package-manager), [Git](https://git-scm.com/downloads), [Laragon](https://laragon.org/download/).

 - Clone the repo
 - Open console and change the folder to ccs3310
 - run the following commands
 <pre>
 composer install
 npm install
 php artisan key:generate
 php artisan migrate:refresh --seed
 npm run dev
 php artisan storage:link
 </pre>
 - from another console, run 
 <pre>php artisan serve</pre>
 - Open your browser and visit the url: 
 <pre>
 http://ccs3310.test
 or
 http://localhost:8000</pre>

## Test login credentials
Email: test@example.com

Password: password


## Third party libraries used
- [Sweet Alert for Laravel](https://realrashid.github.io/sweet-alert/) --> [Read about the new app structure](https://laravel.com/docs/11.x/upgrade#publishing-service-providers). Just add the @include('sweetalert::alert') line, right before the \<body> tag in the resources\views\layouts\app.blade.php

## License

Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

#### Last updated: 14th July 2024# Laravel-Login-with-Social-Accounts
# Laravel-Login-with-Social-Accounts
