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
      environment_details: {{ environment_details }}
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
      environment_details: {{ environment_details }}
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

{%- if couchbase in grains.roles and grains.data_role == 'master' %}
bucket_{{ store }}_{{ environment }}_yves:
  couchbase_bucket.present:
    - name: {{ store }}_{{ environment }}_yves
    - server: {{ pillar.couchbase.host. }}:{{ pillar.couchbase.port }}
    - user: {{ pillar.couchbase.user }}
    - password: {{ pillar.couchbase.password }}
    - size: {{ pillar.couchbase.bucket_size }}
    - replica: {{ pillar.couchbase.replica_size }}
    - bucket_password: {{ pillar.couchbase.password }}
    - require:
      - service: couchbase-server
{%- endif %}

{%- endfor %}
{%- endfor %}
