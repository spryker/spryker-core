mailcatcher:
  gem.installed

/etc/init.d/mailcatcher:
  file.managed:
    - source: salt://mailcatcher/etc/init.d/mailcatcher
    - mode: 0755