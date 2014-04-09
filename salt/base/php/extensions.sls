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

php5-pgsql:
  pkg.installed:
    - require:
      - pkg: php

pkg-config:
  pkg.installed

memcached:
  pecl.installed:
    - defaults: True
    - require:
      - pkg: php-pear
      - pkg: libmemcached-dev
      - pkg: libmemcached11
      - pkg: pkg-config
{% if 'dev' in grains.roles %}
/etc/php5/conf.d/xdebug.ini:
  file.managed:
    - source: salt://php/files/xdebug.ini
    - user: root
    - group: root
    - mode: 644
{% endif %}

/etc/php5/conf.d/20-xdebug.ini:
  file.absent

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
    - template: jinja
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

/etc/php5/conf.d/couchbase.ini:
  file.managed:
    - source: salt://php/files/couchbase.ini
    - user: root
    - group: root
    - mode: 644

# Install CTwig extension
pear-ctwig-channel:
  cmd.run:
    - name: pear channel-discover pear.twig-project.org
    - unless: pear list-channels | grep pear.twig-project.org

pear-ctwig-install:
  cmd.run:
    - name: pear install twig/CTwig
    - unless: pear list -c pear.twig-project.org | grep CTwig
    - require:
      - cmd: pear-ctwig-channel

/etc/php5/conf.d/twig.ini:
  file.managed:
    - source: salt://php/files/twig.ini
    - user: root
    - group: root
    - mode: 644
