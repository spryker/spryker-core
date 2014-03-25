libsqlite3-dev:
  pkg.installed:
    - require_in:
      - pkg: mailcatcher

mailcatcher-init-script:
  file.managed:
    - name: /etc/init.d/mailcatcher
    - mode: 0755
    - source: salt://mailcatcher/etc/init.d/mailcatcher

mailcatcher:
  gem:
    - installed
  service:
    - running
    - enable: True
    - require:
      - file: mailcatcher-init-script
