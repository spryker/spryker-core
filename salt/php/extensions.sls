php-pear:
  pkg.installed:
    - require:
      - pkg: php

# Install memcached extension
libmemcached10:
  pkg.removed

libmemcached11:
  pkg.installed

libmemcached-dev:
  pkg.installed:
   - require:
     - pkg: libmemcached11

memcached:
  pecl.installed:
    - require:
      - pkg: php-pear
      - pkg: libmemcached-dev
      - pkg: libmemcached11

/etc/php5/conf.d/memcached.ini:
  file.managed:
    - source: salt://php/files/memcached.ini
    - user: root
    - group: root
    - mode: 644

# Install Zend OpCache extension - as it's not yet stable, we have to explicityly specify version number
zendopcache:
  pecl.installed:
    - version: 7.0.3
    - require: 
      - pkg: php-pear

/etc/php5/conf.d/opcache.ini:
  file.managed:
    - source: salt://php/files/opcache.ini
    - user: root
    - group: root
    - mode: 644

# Install couchbase extension
include:
  - couchbase.libs

couchbase:
  pecl.installed:
    - require:
      - pkg: php-pear
      - pkg: libcouchbase-dev
      - pkg: libcouchbase2-libevent

