nginx:
  pkg.installed:
    - name: nginx-extras
  service:
    - running
    - require:
      - pkg: nginx-extras
    - watch:
      - file: /etc/nginx/nginx.conf
  file.managed:
    - name: /etc/nginx/nginx.conf
    - source: salt://nginx/files/nginx.conf
  file.managed:
    - name: /etc/nginx/fastcgi_params
    - source: salt://nginx/files/fastcgi_params