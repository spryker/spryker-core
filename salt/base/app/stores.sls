{% from 'settings/init.sls' import settings with context %}
{%- for environment, environment_details in settings.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}

/data/shop/{{ environment }}/shared/config_local_{{ store }}.php:
  file.managed:
    - source: salt://app/files/config/config_local_XX.php
    - template: jinja
    - user: www-data
    - group: www-data
    - mode: 644
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common
    - context:
      environment: {{ environment }}
      settings: {{ settings }}
      store: {{ store }}
      store_details: {{ store_details }}

/data/logs/{{ environment }}/{{ store }}:
  file.symlink:
    - target: /data/shop/production/shared/data/{{ store }}/logs
    - force: True

{%- if 'web' in grains.roles %}
/etc/nginx/sites-available/{{ store }}_{{ environment }}_zed:
  file.managed:
    - source: salt://app/files/nginx/sites-available/XX-zed.conf
    - template: jinja
    - user: root
    - group: root
    - mode: 644
    - context:
      environment: {{ environment }}
      settings: {{ settings }}
      store: {{ store }}
      store_details: {{ store_details }}
    - watch_in:
      - service: nginx

/etc/nginx/sites-available/{{ store }}_{{ environment }}_yves:
  file.managed:
    - source: salt://app/files/nginx/sites-available/XX-yves.conf
    - template: jinja
    - user: root
    - group: root
    - mode: 644
    - context:
      environment: {{ environment }}
      settings: {{ settings }}
      store: {{ store }}
      store_details: {{ store_details }}
    - watch_in:
      - service: nginx


/etc/nginx/sites-enabled/{{ store }}_{{ environment }}_zed:
  file.symlink:
    - target: /etc/nginx/sites-available/{{ store }}_{{ environment }}_zed
    - force: true
    - require:
      - file: /etc/nginx/sites-available/{{ store }}_{{ environment }}_zed
      - file: /etc/nginx/htpasswd-zed
      - file: /etc/nginx/htpasswd-staging
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/{{ store }}_{{ environment }}_yves:
  file.symlink:
    - target: /etc/nginx/sites-available/{{ store }}_{{ environment }}_yves
    - force: true
    - require:
      - file: /etc/nginx/sites-available/{{ store }}_{{ environment }}_yves
    - watch_in:
      - service: nginx

{%- endif %}

{%- endfor %}
{%- endfor %}
