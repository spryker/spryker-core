#
# Macro: Setup one Elasticsearch instance
#

{% macro redis_instance(environment, environment_details, settings) -%}
/data/shop/{{ environment }}/shared/redis:
  file.directory:
    - user: redis
    - group: redis
    - mode: 700
    - makedirs: true

/data/logs/{{ environment }}/redis:
  file.directory:
    - user: redis
    - group: redis
    - mode: 755
    - makedirs: True

/etc/redis/redis_{{ environment }}.conf:
  file.managed:
    - user: root
    - group: root
    - mode: 644
    - template: jinja
    - source: salt://redis/files/redis_instance/etc/redis/redis.conf
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}
      settings: {{ settings }}
    - require:
      - file: /data/shop/{{ environment }}/shared/redis
      - file: /data/logs/{{ environment }}/redis
    - watch_in:
      - service: redis-server

{%- endmacro %}
