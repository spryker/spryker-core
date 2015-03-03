#
# Create postgresql databases, users and privileges
#
#
# This implementation at the moment is 'hacky', as on the day when it was
# written, salt did not support creating schemes and/or managing privileges.
# For each environment we create user and two databases (zed + dump), which
# have the owner set to this user.

{%- from 'settings/init.sls' import settings with context %}
{%- for environment, environment_details in settings.environments.items() %}
{%- for store in pillar.stores %}

# create database user
postgres_users_{{ store }}_{{ environment }}:
  postgres_user.present:
    - name: {{ settings.environments[environment].stores[store].zed.database.username }}
    - password: {{ settings.environments[environment].stores[store].zed.database.password }}
    - require:
      - service: postgresql

# create database - zed
postgres_database_{{ store }}_{{ environment }}_zed:
  postgres_database.present:
    - name: {{ settings.environments[environment].stores[store].zed.database.database }}
    - owner: {{ settings.environments[environment].stores[store].zed.database.username }}
    - require:
      - service: postgresql
      - postgres_user: {{ settings.environments[environment].stores[store].zed.database.username }}

# create database - dump
postgres_database_{{ store }}_{{ environment }}_zed_dump:
  postgres_database.present:
    - name: {{ settings.environments[environment].stores[store].dump.database.database }}
    - owner: {{ settings.environments[environment].stores[store].zed.database.username }}
    - require:
      - service: postgresql
      - postgres_user: {{ settings.environments[environment].stores[store].zed.database.username }}

{% endfor %}
{% endfor %}
