#
# Create MySQL databases, users and privileges
#

{%- from 'settings/init.sls' import settings with context %}
{%- for environment, environment_details in settings.environments.items() %}
{%- for store in pillar.stores %}

# create database - zed
mysql_database_{{ store }}_{{ environment }}_zed:
  mysql_database.present:
    - name: {{ settings.environments[environment].stores[store].zed.database.database }}
    - require:
      - pkg: python-mysqldb
      - service: mysql

# create database - dump
mysql_database_{{ store }}_{{ environment }}_zed_dump:
  mysql_database.present:
    - name: {{ settings.environments[environment].stores[store].dump.database.database }}
    - require:
      - pkg: python-mysqldb
      - service: mysql

# create database user
mysql_users_{{ store }}_{{ environment }}:
  mysql_user.present:
    - name: {{ settings.environments[environment].stores[store].zed.database.username }}
    - host: "{{ salt['pillar.get']('hosting:mysql_network', '%') }}"
    - password: {{ settings.environments[environment].stores[store].zed.database.password }}
    - require:
      - pkg: python-mysqldb
      - service: mysql

# create database permissions (zed database)
mysql_grants_{{ store }}_{{ environment }}_zed:
  mysql_grants.present:
    - grant: all
    - database: {{ settings.environments[environment].stores[store].zed.database.database }}.*
    - user: {{ settings.environments[environment].stores[store].zed.database.username }}
    - host: "{{ salt['pillar.get']('hosting:mysql_network', '%') }}"

# create database permissions (dump database)
mysql_grants_{{ store }}_{{ environment }}_zed_dump:
  mysql_grants.present:
    - grant: all
    - database: {{ settings.environments[environment].stores[store].dump.database.database }}.*
    - user: {{ settings.environments[environment].stores[store].zed.database.username }}
    - host: "{{ salt['pillar.get']('hosting:mysql_network', '%') }}"
{% endfor %}
{% endfor %}
