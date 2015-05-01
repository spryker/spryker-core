#
# Set php.ini configuration files
#

# Web apps (FPM)
/etc/php5/fpm/php.ini:
  file.managed:
    - source: salt://php/files/etc/php5/php.ini
    - require:
      - pkg: php

# CLI
/etc/php5/cli/php.ini:
  file.managed:
    - source: salt://php/files/etc/php5/php.ini
    - require:
      - pkg: php
