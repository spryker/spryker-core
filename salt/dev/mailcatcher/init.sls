mailcatcher:
  gem:
    - installed
  service:
    - running
    - enabled
    - require:
      - file: mailcatcher-init-script

mailcatcher-init-script:
  file.managed:
    - name: /etc/init.d/mailcatcher
    - source: salt://mailcatcher/etc/init.d/mailcatcher



