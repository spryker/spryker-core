{%- if grains.data_role == 'master' %}
{%- for environment, environment_details in pillar.environments.items() %}
{%- for store, store_details in pillar.stores.items() %}
{%- for bucket, bucket_details in pillar.couchbase.buckets.items() %}

bucket_{{ store }}_{{ environment }}_{{ bucket }}:
  couchbase_bucket.present:
    - name: {{ store }}_{{ environment }}_yves
    - server: {{ pillar.couchbase.host }}:{{ pillar.couchbase.port }}
    - user: {{ pillar.couchbase.user }}
    - password: {{ pillar.couchbase.password }}
    - size: {{ bucket_details.bucket_size }}
    - replica: {{ bucket_details.bucket_replica }}
    - bucket_password: {{ pillar.couchbase.password }}
    - require:
      - service: couchbase-server

{%- endfor %}
{%- endfor %}
{%- endif %}
