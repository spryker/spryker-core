{% from 'settings/init.sls' import settings with context %}
/etc/nginx/ssl/saiku.crt:
  file.managed:
    - source: salt://dwh/files/etc/nginx/ssl/saiku.crt
    - mode: 600
    - user: root
    - group: root
    - require:
      - file: /etc/nginx/ssl

/etc/nginx/ssl/saiku.key:
  file.managed:
    - source: salt://dwh/files/etc/nginx/ssl/saiku.key
    - mode: 600
    - user: root
    - group: root
    - require:
      - file: /etc/nginx/ssl

# The default password for dwh (cubes)
/etc/nginx/htpasswd-dwh:
  file.managed:
    - source: salt://dwh/files/etc/nginx/htpasswd-nginx
    - user: www-data
    - group: www-data
    - mode: 640

{%- for environment, environment_details in settings.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}
/etc/nginx/sites-available/{{ store }}_{{ environment }}_dwh.conf:
  file.managed:
    - source: salt://dwh/files/etc/nginx/sites-available/XX-dwh.conf
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

/etc/nginx/sites-enabled/{{ store }}_{{ environment }}_dwh.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/{{ store }}_{{ environment }}_dwh.conf
    - force: true
    - require:
      - file: /etc/nginx/sites-available/{{ store }}_{{ environment }}_dwh.conf
    - watch_in:
      - service: nginx

{%- endfor %}
{%- endfor %}
