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

#/etc/mysql/my.cnf:
#  file.managed:
#    - source: salt://mysql-server/files/etc/mysql/my.cnf

#/etc/mysql/conf.d/binlog.cnf:
#  file.managed:
#    - source: salt://mysql-server/files/etc/mysql/conf.d/binlog.cnf

/etc/mysql/conf.d/strict.cnf:
  file.managed:
    - source: salt://mysql-server/files/etc/mysql/conf.d/strict.cnf

python-mysqldb:
  pkg.installed

{%- for environment, environment_details in pillar.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}

{% set db_users_data = pillar.get('mysql-server', {}).users %}

# create databases
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
    - password:  {{ db_users_data[environment]['password'] }}
    - require:
      - pkg: python-mysqldb
      - service: mysql

# create database permissions
mysql_grants_{{store}}_{{environment}}_zed:
  mysql_grants.present:
    - grant: all
    - database: {{store}}_{{environment}}_zed.*
    - user: {{environment}}
    - host: localhost

{% endfor %}
{% endfor %}
