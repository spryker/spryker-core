mysql-server:
  pkg:
    - installed
    - pkgs:
      - mysql-server
      - mysql-client
  service:
    - running
    - name: mysqld
    - enable: True
    - require:
      - pkg: mysql-server
  mysql_user:
    - present
    - name: root
    - password_hash: '*F3A2A51A9B0F2BE2468926B4132313728C250DBF'
    - require:
      - service: mysqld
