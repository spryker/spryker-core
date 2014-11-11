redis-server:
  pkg:
    - installed

redis-tools:
  pkg:
    - installed

{%- for environment, environment_details in pillar.environments.items() %}
/etc/redis/redis_{{environment}}.conf:
  file.managed:
    - source: salt://redis/files/etc/redis/redis.conf
    - template: jinja
    - context:
      environment: {{ environment }}
    - watch_in:
      - service: redis-server
{% endfor %}

remove-old-redis-config:
  file.absent:
    - name: /etc/redis/redis.conf

redis-init:
  file.managed:
    - name: /etc/init.d/redis-server
    - source: salt://redis/files/etc/init.d/redis-server
    - mode: 755
    - watch_in:
      - service: redis-server

start-redis-server:
  service.running:
    - name: redis-server
    - require:
      - pkg: redis-server

restart-redis:
  cmd.run:
    - name: /etc/init.d/redis-server restart
    - order: last
