{% from 'settings/init.sls' import settings with context %}
{%- for environment, environment_details in settings.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}

/data/shop/{{ environment }}/shared/cubes_{{ store }}.properties:
  file.managed:
    - source: salt://dwh/files/config/cubes_XX.properties
    - template: jinja
    - user: www-data
    - group: www-data
    - mode: 644
    - context:
      environment: {{ environment }}
      settings: {{ settings }}
      store: {{ store }}
      store_details: {{ store_details }}
    - watch_in:
      - service: tomcat7-{{ environment }}
{%- endfor %}
{%- endfor %}