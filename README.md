<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Setup README

This README file provides instructions on how to set up the project on your local machine. Please follow these steps to
get the project up and running.

## Prerequisites

Before you begin, make sure you have the following software installed on your machine:

- Git
- Composer
- Docker

## Steps to Set Up the Project

- Clone the Repository

- Open your terminal and navigate to the directory where you want to set up the project. Then, execute the following
  command to clone the repository:

```bash
git clone <repository_url> <project_folder>
```

Replace <repository_url> with the URL of the repository and <project_folder> with the desired name for the project
folder.

## Install Dependencies

Navigate to the project folder using the terminal:

```bash
cd <project_folder>
```

Run the following command to install the project dependencies using Composer:

```bash
composer install
```

Create Configuration File
Copy the .env_example file and rename it to .env:

```bash
cp .env_example .env
```

You can now edit the .env file to set up your environment configuration, such as database connection settings.

Creat application key

```bash
./vendor/bin/sail php artisan key:generate
```

## Install Frontend packages

```bash
./vendor/bin/sail npm install
```

## Build Frontend

```bash
./vendor/bin/sail npm run build
```

## Launch Docker Environment

Run the following Docker command to start the development environment:

```bash
./vendor/bin/sail up -d --build
```

OR

```bash
docker compose up -docker --build
```

This command will create and start the necessary containers for your project.

## Run Database Migrations and Seeding

Execute the following command to run database migrations and seed the database with initial data:

```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

## Accessing the Application

Once you've completed the setup steps, you can access the application in your web browser by navigating to:

http://localhost:8000
This URL will point to your locally running application.

## Wrapping Up

This project sample is done by [Sutjapong (kengketa)](https://github.com/kengketa), Senior Full-Stack Developer,
Thailand.

