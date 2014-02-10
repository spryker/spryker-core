# Install PHP and extensions
php:
  pkg.installed:
    - pkgs:
      - php5
      - php5-dev      
      - php5-cli
      - php5-fpm
      - php54-zend-optimizer-plus
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
      - php5-ssh2

# Install memcached extension
libmemcached-dev:
  pkg.installed

memcached:
  pecl.installed:
    - require:
      - pkg: php-pear
      - pkg: libmemcached-dev

/etc/php5/conf.d/memcached.ini:
  file.managed:
    - source: salt://php/files/memcached.ini
    - user: root
    - group: root
    - mode: 644

# Install Zend OpCache extension
zendopcache:
  pecl.installed:
    - require: pkg: php-pear

/etc/php5/conf.d/opcache.ini:
  file.managed:
    - source: salt://php/files/opcache.ini
    - user: root
    - group: root
    - mode: 644

# Install couchbase extension
couchbase:
  pecl.installed:
    - require:
      - pkg: php-pear
