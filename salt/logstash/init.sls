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

/etc/default/logstash:
  file.managed:
    - source: salt://logstash/files/etc/default/logstash

/etc/default/logstash-web:
  file.managed:
    - source: salt://logstash/files/etc/default/logstash-web

/etc/logstash/conf.d/lumberjack.conf:
  file.managed:
    - source: salt://logstash/files/etc/logstash/conf.d/lumberjack.conf
