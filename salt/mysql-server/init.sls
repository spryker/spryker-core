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

## mysql user states
{% if 'users' in pillar['mysql-server'] %}
{% for eachuser in pillar['mysql-server']['users'] %}
{% set username = eachuser['user'] %}
mysql_users_{{username}}:
  mysql_user.present:
    - name: {{username}}
    - host: {{eachuser['host']}}
    - password: {{eachuser['password']}}
    - require:
      - pkg: python-mysqldb
      - service: mysql

## mysql user permission
{% if 'permissions' in eachuser%}
{% for eachgrant in eachuser['permissions'] %}
mysql_grants_{{username}}_{{eachgrant['database']}}:
  mysql_grants.present:
    - grant: {{eachgrant['grant']}}
    - database: {{eachgrant['database']}}
    - user: {{username}}
    - host: {{eachuser['host']}}
{% endfor %}
{% endif %}

{% endfor %}
{% endif %}
{% endif %}
{% endif %}
