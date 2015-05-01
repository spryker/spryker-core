# php.ini - for web apps
/etc/php5/fpm/php.ini:
  file.managed:
    - source: salt://php/files/php.ini

/etc/php5:
  file.directory:
  - mode: 755

# php.ini - for CLI
/etc/php5/cli/php.ini:
  file.managed:
    - source: salt://php/files/php.ini

/etc/php5/fpm/php-fpm.conf:
  file.managed:
    - source: salt://php/files/php-fpm.conf

/etc/php5/fpm/pool.d/www.conf:
  file.absent
