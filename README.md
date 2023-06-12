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
oraz automatyczna konfiguracja .env
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
### Aby krzystać z podstawowych poleceń php należy wykorzystywać program sail znajdujący się wewnątrz katalogu vendor
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
### Jeżeli chodzi o pliki oraz strukturę aplikacji, to są one współdzielone z kontenerem za pośrednictwem volumenow

<h1 align='center'> Etap IV - Projekt </h1> 

Projekt ma na celu stworzenie aplikacji internetowej, która pozwoli użytkownikom reklamować swoje usługi fryzjerskie. Aplikacja zapewni fryzjerom platformę do tworzenia profili i promowania swoich firm wśród potencjalnych klientów. 

Wymagania funkcjonalne
- Rejestracja i logowanie użytkowników
- Możliwość tworzenia usług fryzjerskich
- Możliwość zarządzania swoimi usługami
- Funkcja wyszukiwania umożliwiająca użytkownikom znalezienie fryzjerów na podstawie lokalizacji.

Wymagania niefunkcjonalne
- Aplikacja powinna być kompatybilna z różnymi przeglądarkami takimi jak Google Chrome, Firefox
- Aplikacja powinna działać niezależnie od systemu operacyjnego
- Dane użytkowników powinny być bezpiecznie przechowywane
- System korzysta z bazy MySQL wewnątrz środowiska Docker.
- System jest wykonany w technologii Laravel


<h1 align='center'> Etap V - Docker </h1> 

Jeżeli chodzi o Dockera w Laravelu to jest on niezwykle prosty dla developera. Wszystko spraszcza się do instalacji sail.
Natomiast już z persektywy dev-opsa sytuacja nie jest tak prosta, ponieważ sail niewystarczy do zarządzania projektem na produkcji.
Dlatego najlepszą opcją jest przygotowanie własnego środowiska.

Struktura projektu
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

# Set the container to listen on port 8000
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

Jeżeli na tym etapie aplikacja działa poprawnie, możemy zaadaptować ją do swarm

<img src="https://github.com/Gabrysiewicz/S6P_Programowanie-aplikacji-w-chmurze-obliczeniowej/blob/main/images/swarm.jpg">

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
```
➜  Laravel git:(main) ✗ docker swarm init
```
```
Swarm initialized: current node (jr72sc3dnds7b41xvoktodoib) is now a manager.

To add a worker to this swarm, run the following command:

    docker swarm join --token SWMTKN-1-1gpyken135hf4e4n4m70hkjllxhm9on7mpn5bpyf3wvm82udrx-2oa54rvpxic3lsaejgqb19qg5 192.168.65.4:2377

To add a manager to this swarm, run 'docker swarm join-token manager' and follow the instructions.
```

```
➜  Laravel git:(main) ✗ docker stack deploy -c docker-stack.yaml laravel
```
```
Ignoring unsupported options: build

Creating network laravel_swarm
Creating service laravel_mysql
Creating service laravel_app
```

```
➜  Laravel git:(main) ✗ docker stack ls
```
```
NAME      SERVICES   ORCHESTRATOR
laravel   2          Swarm
```

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
