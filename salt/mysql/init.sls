mysql:
  pkg:
    - installed
    - name: mysql-server

  service:
    - running
    - name: mysql
    - enable: True
    - watch:
      - pkg: mysql-server

/etc/mysql/my.cnf:
  file.managed:
    - source: salt://mysql/files/etc/mysql/my.cnf

#  mysql_user:
#    - present
#    - name: root
#    - password_hash: '*F3A2A51A9B0F2BE2468926B4132313728C250DBF'
#    - require:
#      - service: mysql
