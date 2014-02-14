nginx:
  pkg.installed:
    - name: logstash
  service:
    - running
    - require:
      - pkg: logstash
#    - watch:
#      - file: /etc/nginx/nginx.conf

#/etc/nginx/nginx.conf:
#  file.managed:
#    - source: salt://nginx/files/nginx.conf

#/etc/nginx/fastcgi_params:
#  file.managed:
#    - source: salt://nginx/files/fastcgi_params
