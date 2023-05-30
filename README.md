<h1 align='center'> Projekt </h1>

Repozytorium poświęcone pracy nad projektem zaliczeniowym z przedmiotu `Programowanie-aplikacji-w-chmurze-obliczeniowej` (semestr 6, Software Engineering).

Projekt ma skupiać się na zastosowaniu środowiska Docker w aplikacjach chmurowych. 

Technologia w której dana aplikacja ma zostać stworzona: dowolne

<h2 align='center'>  Etap 1 - Przygotowanie środowiska </h2>

### Tworzymy bazową aplikację
```
composer create-project --prefer-dist laravel/laravel your-project-name
```

### Przechodzimy do aplikacji
```
cd your-project-name
```

### Struktura nowego projektu
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

### Tworzymy Dockerfile
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

### Tworzymy docker-compose
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

### Uruchamiamy bazowy projekt
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

### Aplikacja
<img src="https://github.com/Gabrysiewicz/S6P_Programowanie-aplikacji-w-chmurze-obliczeniowej/blob/main/images/laravel_initial_site.jpg" >

-->

<h2 align='center'> Etap II - Instalacja Docker </h2>

### Instalacja saila za pośrednictwem composer
```
composer require laravel/sail --dev
```

### Utworzenie podstawowej konfiguracji docker-compose dla larvel
```
php artisan sail:install
```
Efektem polecenia jest plik docker-compose.yml
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
### Uruchomienie Laravela w środowisku Docker
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
<img src="https://github.com/Gabrysiewicz/S6P_Programowanie-aplikacji-w-chmurze-obliczeniowej/blob/main/images/laravel_initial_site.jpg" >

<h1 align='center'> Etap III - Praca z projektem </h1>

# Sail
### Aby krzystać z podstawowych poleceń php należy wykorzystywać program sail zainstalowany wewnątrz katalogu vendor
```
./vender/bin/sail php --version
```

### Warto jest skorzystać z aliasu w celu ułatwienia pracy
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

### Można również wykonywać polecenia wewnątrz kontenera
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

<h1 align='center'> Etap IV - Laravel </h1> 

TODO:
- [ ] Cel 
- [ ] Zakres
- [ ] Kontekst
- [ ] Wymagania funkcjonalne
- [ ] Wymagania niefunkcjonalne
- [ ] etc

<h1 align='center'> Etap V - Docker </h1> 

TODO:
- [ ] Szczegółowy opis docker-compose
- [ ] Objaśnienie .env
- [ ] Potencjalny Swarm
