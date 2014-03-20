nginx:
  pkg.installed:
    - name: nginx-extras
  service:
    - running
    - require:
      - pkg: nginx-extras
    - watch:
      - file: /etc/nginx/nginx.conf

/etc/nginx/nginx.conf:
  file.managed:
    - source: salt://nginx/files/nginx.conf

/etc/nginx/fastcgi_params:
  file.managed:
    - source: salt://nginx/files/fastcgi_params
    - watch_in:
      - service: nginx

/etc/nginx/conf.d:
  file.recurse:
    - source: salt://nginx/files/conf.d
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/default:
  file.absent:
    - watch_in:
      - service: nginx

