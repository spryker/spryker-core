netatalk:
  pkg:
    - installed
    - name: netatalk

  service:
    - running
    - name: netatalk
    - enable: True
    - watch:
      - pkg: netatalk

/etc/netatalk/afpd.conf:
  file.managed:
    - source: salt://netatalk/files/etc/afpd.conf
