
# Voyatek Group Backend developer Assessment

Development of simple CRUD Blog/Posts APIs with user interactivity.



## Introduction

This api contains the following endpoints

- Blogs (CRUD Operations)
- Posts (CRUD Operations)
- Posts Interactivity (Like and Comments)

## How to Run the Program

To run this Program:
- Install composer
- Install Dependencies
```
    composer install
```

- Copy and rename the .env.example file to .env
- Create database
- Edit the .env file and add the database credentials
- Run migration 

```
php artisan migrate

```
- Run the Database seeder to create test user

```
php artisan db:seed --class=UserSeeder
```

- Start the Program

```
    php artisan migrate
```

## Postman Documentation

The Documentation can be found below

https://documenter.getpostman.com/view/34587841/2sA3kUFMje