/home/vagrant/dev:
  file.symlink:
    - target: /data/shop/development/current
    - force: true

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
    - template: jinja
