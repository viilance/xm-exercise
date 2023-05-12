## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Clone the repository

    git clone https://github.com/viilance/xm-exercise.git

Switch to the repo folder

    cd xm-exercise

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file
(I used mailtrap for sending out emails) and make sure to add rapid api key and host

    cp .env.example .env

Make sure you have the [Docker](https://www.docker.com) up and running, and have the required database driver installed

    sudo apt-get install php-mysql

Start the docker container by running the laravel sail command

    ./vendor/bin/sail up -d

Generate the application key

    php artisan key:generate

You can now access the application at http://localhost

**TL;DR command list**

    git clone https://github.com/viilance/xm-exercise.git
    cd xm-exercise
    composer install
    cp .env.example .env
    sudo apt-get install php-mysql
    ./vendor/bin/sail up -d
    php artisan key:generate
