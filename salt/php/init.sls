# Install PHP and extensions
php:
  pkg.installed:
    - pkgs:
      - php5
      - php5-dev      
      - php5-cli
      - php5-fpm
      - php-pear
      - php5-curl
      - php5-gd
      - php5-gmp
      - php5-imagick
      - php5-intl
      - php5-mcrypt
      - php5-memcache
      - php5-mysql
      - php5-pgsql
      - libssh2-php

include:
  - .extensions