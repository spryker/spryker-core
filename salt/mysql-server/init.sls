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

# create databases
{%- for environment, environment_details in pillar.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}

mysql_database_{{store}}_{{environment}}_zed:
  mysql_database.present:
    - name: {{store}}_{{environment}}_zed
    - require:
      - pkg: python-mysqldb
      - service: mysql

# create database users
mysql_users_{{store}}_{{environment}}:
  mysql_user.present:
    - name: {{environment}}
    - host: localhost
    - password: todo
    - require:
      - pkg: python-mysqldb
      - service: mysql

mysql_grants_{{username}}_{{eachgrant['database']}}:
  mysql_grants.present:
    - grant: all
    - database: {{store}}_{{environment}}_zed
    - user: {{environment}}
    - host: localhost


{% endfor %}
{% endfor %}







# create database permissions
