mailcatcher:
  gem.installed

/etc/init/mailcatcher.conf:
  file.managed:
    - source: salt://mailcatcher/etc/init/mailcatcher.conf