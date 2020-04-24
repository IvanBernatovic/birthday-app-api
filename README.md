# Birthday reminders API

This app is Laravel 7 app for acompanying client app Birthday reminders Client. It's API only repo for the app for keeping all your birthdays and gift lists in one place. The app can be tried on at https://birthdays.techyfingers.com. 

You can add, update and delete birthdays. You can set global email reminders for birthdays and individual reminders for each birthday. You can also keep track of giftlist per birthday.

The app has 100% test coverage and since it's dockerized is easily deployable to any environment. It uses docker-compose for managing multiple docker images such as PHP-FPM 7.4, MariaDB, Redis, Nginx etc.

To install the API app follow these:
1. clone this repo
2. run `cp .env.example .env`
3. Fill in .env file. DB credentials are arbitrary and will be used when building docker image for the first time.
4. Run `docker-compose build redis app mariadb nginx queue scheduler`.
5. Run `docker-compose up -d redis app mariadb nginx queue scheduler`. This will take some time for the first time because composer dependencies will be installed in this step.
6. Run `docker-compose exec app php artisan key:generate`, `docker-compose exec app php artisan jwt:secret` and `docker-compose exec app php artisan migrate`
7. App is now accessible on localhost:8000