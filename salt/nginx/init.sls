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