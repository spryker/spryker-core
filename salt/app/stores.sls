{%- for environment, environment_details in pillar.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}


/data/shop/{{ environment }}/shared/config_local_{{ store }}.php:
  file.managed:
    - source: salt://app/files/config/config_local_XX.php
    - template: jinja
    - user: www-data
    - group: www-data
    - mode: 640
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}
      store: {{ store }}
      store_details: {{ store_details }}



{%- endfor %}
{%- endfor %}
