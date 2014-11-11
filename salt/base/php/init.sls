# Install PHP and extensions
libmysqlclient18:
  pkg.installed:
    - version: 5.6.21-1~dotdeb.1

mysql-common:
  pkg.installed:
    - version: 5.6.21-1~dotdeb.1

php:
  pkg.installed:
    - pkgs:
      - php5
      - php5-dev
      - php5-cli
      - php5-fpm
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
{% if 'dev' in grains.roles %}
      - php5-xdebug
{% endif %}

include:
  - .config
  - .extensions
  - .fpm
