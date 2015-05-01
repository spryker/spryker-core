#
# Setup and run logstash
#

logstash:
  pkg:
    - installed
  service.running:
    - enable: true
    - require:
      - pkg: logstash
      - file: /etc/logstash/conf.d/spryker.conf

/etc/logstash/conf.d/spryker.conf:
  file.managed:
    - source: salt://elk/files/etc/logstash/conf.d/spryker.conf
    - template: jinja
    - require:
      - pkg: logstash
    - watch_in:
      - service: logstash
