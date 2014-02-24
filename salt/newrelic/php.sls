include:
  - php
  - newrelic

newrelic-php5:
  pkg.installed:
    - name: newrelic-php5
  file.managed:
    - name: /etc/php5/mods-available/newrelic.ini
    - source: salt://newrelic/files/etc/php5/mods-available/newrelic.ini
    - template: jinja
    - watch_in:
      - service: php5-fpm
    - require:
      - pkgrepo: newrelic-sysmond
  cmd.wait:
    - name: php5enmod newrelic
    - watch:
      - file: newrelic-php5
    - watch_in:
      - service: php5-fpm

/etc/php5/conf.d/newrelic.ini:
  file.absent:
    - require:
      - pkg: newrelic-php5