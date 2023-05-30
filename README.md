<h1 align='center'> Projekt </h1>
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
