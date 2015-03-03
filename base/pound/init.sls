#
# Install and configure pound, and SSL-Termination proxy
#

pound:
  pkg.installed:
    - name: pound
  service:
    - running
    - require:
      - pkg: pound
      - file: /etc/default/pound
      - file: /etc/pound/certs/star_project_yz_com
      - file: /etc/pound/certs/star_project_yz_de
      - file: /etc/pound/certs/star_spryker_dev
    - watch:
      - file: /etc/pound/pound.cfg

/etc/pound/pound.cfg:
  file.managed:
    - source: salt://pound/files/etc/pound/pound.cfg
    - require:
      - pkg: pound

/etc/default/pound:
  file.managed:
    - source: salt://pound/files/etc/default/pound
    - require:
      - pkg: pound

/etc/pound/certs:
  file.directory:
    - user: root
    - group: root
    - mode: 755
    - require:
      - pkg: pound

/etc/pound/certs/star_project_yz_com:
  file.managed:
    - source: salt://pound/files/etc/pound/certs/star_project_yz_com
    - require:
      - file: /etc/pound/certs

/etc/pound/certs/star_project_yz_de:
  file.managed:
    - source: salt://pound/files/etc/pound/certs/star_project_yz_de
    - require:
      - file: /etc/pound/certs

/etc/pound/certs/star_spryker_dev:
  file.managed:
    - source: salt://pound/files/etc/pound/certs/star_spryker_dev
    - require:
      - file: /etc/pound/certs
