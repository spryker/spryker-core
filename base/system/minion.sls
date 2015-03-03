#
# Setup salt minion parameters to allow enable mine mechanism
#

salt-minion:
  service.running:
    - enable: True
    - watch:
      - file: /etc/salt/minion.d/mine.conf
  file.managed:
    - name: /etc/salt/minion.d/mine.conf
    - source: salt://system/files/etc/salt/minion.d/mine.conf
