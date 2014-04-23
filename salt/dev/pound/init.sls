pound:
  pkg.installed:
    - name: pound
  service:
    - running
    - require:
      - pkg: pound
    - watch:
      - file: /etc/pound/pound.cfg

/etc/pound/pound.cfg:
  file.managed:
    - source: salt://pound/files/etc/pound/pound.cfg

/etc/default/pound:
  file.managed:
    - source: salt://pound/files/etc/default/pound

/etc/pound/certs/star_project_yz_com:
  file.managed:
    - source: salt://pound/files/etc/pound/certs/star_project_yz_com

/etc/pound/certs/star_project_yz_de:
  file.managed:
    - source: salt://pound/files/etc/pound/certs/star_project_yz_de
