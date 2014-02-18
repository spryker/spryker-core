/etc/php5/fpm/php.ini:
  file.managed:
    - source: salt://php/files/php.ini

/etc/php5/fpm/php-fpm.conf:
  file.managed:
    - source: salt://php/files/php-fpm.conf
    
