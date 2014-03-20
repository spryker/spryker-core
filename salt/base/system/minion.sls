salt-minion:
  service.running:
    - enable: True
    - watch:
      - file: /etc/salt/minion.d/mine.conf
  file.managed:
    - name: /etc/salt/minion.d/mine.conf
    - source: salt://system/files/mine.conf
