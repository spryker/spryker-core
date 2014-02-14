logstash:
  pkg.installed:
    - name: logstash
  service:
    - running
    - require:
      - pkg: logstash
#    - watch:
#      - file: /etc/logstash/logstash.conf

#/etc/logstash/logstash.conf:
#  file.managed:
#    - source: salt://logstash/files/logstash.conf

#/etc/nginx/fastcgi_params:
#  file.managed:
#    - source: salt://nginx/files/fastcgi_params
