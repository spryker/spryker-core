logstash:
  pkg.installed:
    - name: logstash
  service:
    - running
    - require:
      - pkg: logstash
    - watch:
      - file: /etc/default/logstash
      - file: /etc/logstash/conf.d/lumberjack.conf

logstash-web:
  service:
{%- if 'kibana' in grains.roles %}
    - running
    - enable: True
{%- else %}
    - dead
    - enable: False
{%- endif %}

/etc/default/logstash:
  file.managed:
    - source: salt://logstash/files/etc/default/logstash
    - template: jinja

/etc/default/logstash-web:
  file.managed:
    - source: salt://logstash/files/etc/default/logstash-web
    - template: jinja

/etc/logstash/conf.d/lumberjack.conf:
  file.managed:
    - source: salt://logstash/files/etc/logstash/conf.d/lumberjack.conf
    - template: jinja
