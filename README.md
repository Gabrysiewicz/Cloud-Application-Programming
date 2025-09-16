<table align='center'>
  <tr> <td colspan='3'> <img width="884px" > </td> </tr>
  <tr> <td colspan="3" align='center'> <img src='https://pollub.pl/fcp/sPREgARcJNScXKxEMUA9DA2ltVyVUFDFqUVJWazkALV96cypSPhRaWXI0D1ZUShtGPlY7MRk8VhIgXFdYVmsjBzRWKw/_global/public/biuro_rektora/zdjecia/logo_politechniki_lubelskiej.jpg' width="400px" height="400px"></td> </tr>
  <tr> <td> Kamil Gabrysiewicz </td> <td> Index: 95400 </td> <td> Grupa: 6.3 </td> </tr>  
  <tr> <td> Jakub Furtak </td> <td> Index: 95393 </td> <td> Grupa: 6.3 </td> </tr>  
  <tr> <td> Session 6 </td> <td colspan='2' align='center'> Cloud Application Programming </td> </tr>  
</table>

<h1 align='center'>Project</h1>

This repository is dedicated to the coursework project for the subject `Cloud Application Programming` (Semester 6, Software Engineering).

The project focuses on using **Docker** in cloud applications.  

The technology for creating the application: any of your choice.

<h2 align='center'>Stage 1 – Setting Up the Environment</h2>

### Creating the base application
```
composer create-project --prefer-dist laravel/laravel your-project-name
```

### Moving into the application directory
```
cd your-project-name
```

### Structure of the new project

```
.
├── README.md
├── app
├── artisan
├── bootstrap
├── composer.json
├── composer.lock
├── config
├── database
├── package.json
├── phpunit.xml
├── public
├── resources
├── routes
├── storage
├── tests
├── vendor
└── vite.config.js
```

<!--

### Dockerfile Creation
```
# Use the official PHP image as the base image
FROM php:8.2-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the project files to the working directory
COPY . .

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Set the container to listen on port 8000
EXPOSE 8000

# Start the PHP development server
CMD php artisan serve --host=0.0.0.0 --port=8000
```

### docker-compose create
```
version: '3'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - 8000:8000
```

### Run base project
```
docker-compose up 
```
```
cloud-app-app-1  |
cloud-app-app-1  |    INFO  Server running on [http://0.0.0.0:8000].
cloud-app-app-1  |
cloud-app-app-1  |   Press Ctrl+C to stop the server
cloud-app-app-1  |
```

### App
<img src="https://github.com/Gabrysiewicz/S6P_Programowanie-aplikacji-w-chmurze-obliczeniowej/blob/main/images/laravel_initial_site.jpg" >

-->

<h2 align='center'>Stage II – Installing Docker</h2>

### Installing Sail via Composer
```
composer require laravel/sail --dev
```

### Creating a basic Docker Compose configuration for Laravel

```
php artisan sail:install
```
The command produces a `docker-compose.yml` file
```
version: '3'
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.2
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.2/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '${APP_PORT:-80}:80'
            - '${VITE_PORT:-5173}:${VITE_PORT:-5173}'
        environment:
            WWWUSER: '${WWWUSER}'
            LARAVEL_SAIL: 1
            XDEBUG_MODE: '${SAIL_XDEBUG_MODE:-off}'
            XDEBUG_CONFIG: '${SAIL_XDEBUG_CONFIG:-client_host=host.docker.internal}'
            IGNITION_LOCAL_SITES_PATH: '${PWD}'
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - mysql
    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
        networks:
            - sail
        healthcheck:
            test:
                - CMD
                - mysqladmin
                - ping
                - '-p${DB_PASSWORD}'
            retries: 3
            timeout: 5s
networks:
    sail:
        driver: bridge
volumes:
    sail-mysql:
        driver: local
```
as well as automatic `.env` configuration
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:WMn3esYxnL16ja1TY4RH4irqRvGJysfZwcZLXf/p5Vs=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

### Running Laravel in the Docker environment

```
./vendor/bin/sail up
```
```
project-laravel.test-1  |
project-laravel.test-1  |    INFO  Server running on [http://0.0.0.0:80].
project-laravel.test-1  |
project-laravel.test-1  |   Press Ctrl+C to stop the server
project-laravel.test-1  |
```
<img src="https://github.com/Gabrysiewicz/Cloud-Application-Programming/blob/main/images/laravel_initial_site.jpg" >

<h1 align='center'> Stage III - Work with project </h1>

# Sail
### To use basic PHP commands, you need to use the `sail` program located inside the `vendor` directory
```
./vender/bin/sail php --version
```

### It is recommended to use an alias to simplify your workflow

```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

### You can also execute commands inside the container
```
docker exec -it 46f759d3d748 bash
```
```
root@46f759d3d748:/var/www/html# ls
README.md  artisan    composer.json  config    docker-compose.yml  phpunit.xml  resources  storage  vendor
app        bootstrap  composer.lock  database  package.json        public       routes     tests    vite.config.js

root@46f759d3d748:/var/www/html# php --version
PHP 8.2.6 (cli) (built: May 12 2023 06:24:00) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.2.6, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.6, Copyright (c), by Zend Technologies
    with Xdebug v3.2.0, Copyright (c) 2002-2022, by Derick Rethans
    
root@46f759d3d748:/var/www/html# composer --version
Composer version 2.5.6 2023-05-24 09:14:18

root@46f759d3d748:/var/www/html# php artisan migrate

   INFO  Nothing to migrate.

```
### Regarding files and the application structure, they are shared with the container via volumes


<h1 align='center'> Stage IV - Project </h1> 

The project aims to create a web application that allows users to promote their hairdressing services. The application provides hairdressers with a platform to create profiles and advertise their businesses to potential clients.

**Functional Requirements**
- User registration and login
- Ability to create hairdressing services
- Ability to manage own services
- Search functionality allowing users to find hairdressers based on location

**Non-Functional Requirements**
- The application should be compatible with various browsers such as Google Chrome and Firefox
- The application should work independently of the operating system
- User data should be stored securely
- The system uses a MySQL database inside the Docker environment
- The system is built using the Laravel framework

## Technologies
<img src="https://github.com/Gabrysiewicz/Cloud-Application-Programming/blob/main/images/laravel.png">

Laravel is a popular development framework used to build modern web applications. It uses the PHP language and provides many tools and features that simplify the software development process.  
One of Laravel’s greatest advantages is its comprehensive ORM system called **Eloquent**, which allows easy database interaction without the need to write complex SQL queries.

<img src="https://github.com/Gabrysiewicz/Cloud-Application-Programming/blob/main/images/sail.jpg">

**Sail** is the official tool provided by Laravel. It simplifies deploying and running Laravel applications through containerization using Docker. These containers include all the necessary dependencies for the Laravel application to run smoothly.

<h1 align='center'>Stage V – Docker</h1>

Regarding Docker in Laravel, it is extremely simple for developers. Most tasks are reduced to installing Sail.  
However, from a DevOps perspective, the situation is not as straightforward because Sail alone is not enough to manage a project in production.  
Therefore, the best option is to set up a custom environment.

**Project Structure**
```
├── Dockerfile
├── README.md
├── app
├── artisan
├── bootstrap
├── composer.json
├── composer.lock
├── config
├── database
├── docker-compose.yaml
├── docker-stack.yaml
├── package.json
├── phpunit.xml
├── public
├── resources
├── routes
├── src
├── storage
├── tests
├── vendor
└── vite.config.js
```

## Dockerfile
```
# Use the official PHP image as the base image
FROM php:8.2-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the project files to the working directory
COPY . .

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Set the container to listen on port 80
EXPOSE 80

# Start the PHP development server
CMD php artisan serve --host=0.0.0.0 --port=80
```

## docker-compose.yaml:
```
version: '3'
services:
  app:
      build:
          context: .
          dockerfile: Dockerfile
      image: my-app:latest
      ports:
        - '${APP_PORT:-80}:80'
      volumes:
        - '.:/var/www/html'
      networks:
        - swarm
      depends_on:
        - mysql

  mysql:
      image: 'mysql/mysql-server:8.0'
      ports:
        - '${FORWARD_DB_PORT:-3306}:3306'
      environment:
        MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
        MYSQL_ROOT_HOST: '%'
        MYSQL_DATABASE: '${DB_DATABASE}'
        MYSQL_USER: '${DB_USERNAME}'
        MYSQL_PASSWORD: '${DB_PASSWORD}'
        MYSQL_ALLOW_EMPTY_PASSWORD: 1
      volumes:
        - 'database:/var/lib/mysql'
      networks:
        - swarm
      healthcheck:
        test:
          - CMD
          - mysqladmin
          - ping
          - '-p${DB_PASSWORD}'
        retries: 3
        timeout: 5s

networks:
  swarm:
    driver: bridge

volumes:
  database:
      driver: local
```

If the application works correctly at this stage, we can adapt it to Docker Swarm

<img src="https://github.com/Gabrysiewicz/Cloud-Application-Programming/blob/main/images/swarm.jpg">

## docker-stack.yaml
```
version: '3'
services:
  app:
      build:
          context: .
          dockerfile: Dockerfile
      image: my-app:latest
      ports:
        - '${APP_PORT:-80}:80'
      volumes:
        - '.:/var/www/html'
      networks:
        - swarm
      deploy:
        replicas: 3
        resources:
          limits:
            cpus: "0.5"
            memory: 512M
      depends_on:
        - mysql

  mysql:
      image: 'mysql/mysql-server:8.0'
      ports:
        - '${FORWARD_DB_PORT:-3306}:3306'
      environment:
        MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
        MYSQL_ROOT_HOST: '%'
        MYSQL_DATABASE: '${DB_DATABASE}'
        MYSQL_USER: '${DB_USERNAME}'
        MYSQL_PASSWORD: '${DB_PASSWORD}'
        MYSQL_ALLOW_EMPTY_PASSWORD: 1
      volumes:
        - 'database:/var/lib/mysql'
      networks:
        - swarm
      deploy:
        replicas: 2
        resources:
          limits:
            cpus: "0.5"
            memory: 512M
      healthcheck:
        test:
          - CMD
          - mysqladmin
          - ping
          - '-p${DB_PASSWORD}'
        retries: 3
        timeout: 5s

networks:
  swarm:
    driver: overlay

volumes:
  database:
    driver: local
```

## Swarm

### Swarn Initialization
```
➜  Laravel git:(main) ✗ docker swarm init
```
```
Swarm initialized: current node (jr72sc3dnds7b41xvoktodoib) is now a manager.

To add a worker to this swarm, run the following command:

    docker swarm join --token SWMTKN-1-1gpyken135hf4e4n4m70hkjllxhm9on7mpn5bpyf3wvm82udrx-2oa54rvpxic3lsaejgqb19qg5 192.168.65.4:2377

To add a manager to this swarm, run 'docker swarm join-token manager' and follow the instructions.
```

### Building a Swarm named `laravel` based on `docker-stack.yaml`

```
➜  Laravel git:(main) ✗ docker stack deploy -c docker-stack.yaml laravel
```
```
Ignoring unsupported options: build

Creating network laravel_swarm
Creating service laravel_mysql
Creating service laravel_app
```

### Listing of swarm

```
➜  Laravel git:(main) ✗ docker stack ls
```
```
NAME      SERVICES   ORCHESTRATOR
laravel   2          Swarm
```

### Show container inside of swarm

```
➜  Laravel git:(main) ✗ docker stack ps laravel
```
```
ID             NAME              IMAGE                    NODE             DESIRED STATE   CURRENT STATE            ERROR     PORTS
nejxeok0746y   laravel_app.1     my-app:latest            docker-desktop   Running         Running 59 seconds ago
pr69tuiw1baj   laravel_app.2     my-app:latest            docker-desktop   Running         Running 59 seconds ago
d5q11ojz485c   laravel_app.3     my-app:latest            docker-desktop   Running         Running 59 seconds ago
tl7dqna1vcmj   laravel_mysql.1   mysql/mysql-server:8.0   docker-desktop   Running         Running 31 seconds ago
sizjegy0b3x6   laravel_mysql.2   mysql/mysql-server:8.0   docker-desktop   Running         Running 31 seconds ago
nxtibw63kgvn   laravel_mysql.3   mysql/mysql-server:8.0   docker-desktop   Running         Running 31 seconds ago
```

## Export
For exporting, we can use GitHub, among other options. The `.gitignore` file will already include `.env` and `vendor`.

```
➜ git init
```
```
➜ git add .
```
```
➜ git commit -m"All done, Ready to export"
```
```
➜ git push origin main
```

Exporting the database if its contents are needed; for this purpose, we can use `mysqldump`
```
mysqldump -u your_database_user -p your_database_name > exported_database.sql
```

Export conf files:
 - .env
 - ./config
 - ./database

## Import
After downloading the project, we can reconfigure the environment variables if needed.

Using the command `composer install`, we install the necessary packages based on the `composer.json` file.

We import the database.

We clear the cache:
```
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

If necessary, appropriate permissions should be granted to files and directories.

<h2 align='center'>Stage VI – Conclusions</h2>

As part of the project, a comprehensive system was created that allows hairdressers to create and manage their profiles, where they can showcase their skills and experience, as well as share information about availability and offered services. Thanks to using Laravel, database management was simplified, enabling reliable storage of information about hairdressers and their services.

Laravel Sail provided not only application reliability but also portability. By using Docker containers, the application was fully isolated from the operating system and other resources, minimizing the risk of failures and ensuring a stable working environment. Additionally, containerization made it possible to run the application on different platforms, allowing it to be transferred to other environments without extensive adjustments or configuration.

The application was designed to be intuitive and user-friendly. This allowed hairdressers to easily create and update their profiles and add service information, effectively promoting their business.
