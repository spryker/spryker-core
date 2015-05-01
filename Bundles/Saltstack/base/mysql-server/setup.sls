#
# Install and configure local MySQL server for development / QA
# For production setup, a high-available solution or DBaaS (database-as-a-service) should be used
#

mysql-server:
  pkg:
    - installed
    - name: mysql-server-5.6

mysql:
  service:
    - running
    - enable: True
    - watch:
      - pkg: mysql-server
      - file: /etc/mysql/my.cnf

/etc/mysql/my.cnf:
  file.managed:
    - source: salt://mysql-server/files/etc/mysql/my.cnf

/etc/mysql/conf.d/strict.cnf:
  file.managed:
    - source: salt://mysql-server/files/etc/mysql/conf.d/strict.cnf

python-mysqldb:
  pkg.installed
