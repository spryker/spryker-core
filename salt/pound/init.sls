pound:
  pkg.installed:
    - name: pound
  service:
    - running
    - require:
      - pkg: pound
    - watch:
      - file: /etc/pound.conf

/etc/pound/pound.conf:
  file.managed:
    - source: salt://pound/files/etc/pound/pound.conf

/etc/default/pound:
  file.managed:
    - source: salt://pound/files/etc/default/pound
