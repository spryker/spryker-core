mysql-server:
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
    - source: salt://mysql-server/files/etc/mysql/my.cnf

/etc/mysql/conf.d/binlog.cnf:
  file.managed:
    - source: salt://mysql-server/files/etc/mysql/conf.d/binlog.cnf

/etc/mysql/conf.d/strict.cnf:
  file.managed:
    - source: salt://mysql-server/files/etc/mysql/conf.d/strict.cnf

python-mysqldb:
  pkg.installed

## mysql database states
{% if 'databases' in pillar['mysql-server'] %}
{% for eachdb in pillar['mysql-server']['databases'] %}
mysql_database_{{eachdb}}:
  mysql_database.present:
    - name: {{eachdb}}
    - require:
      - pkg: python-mysqldb
      - service: mysql
{% endfor %}
{% endif %}

