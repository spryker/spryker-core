/etc/nginx/ssl/saiku.crt:
  file.managed:
    - source: salt://dwh/files/etc/nginx/ssl/saiku.crt
    - mode: 600
    - user: root
    - group: root

/etc/nginx/ssl/saiku.key:
  file.managed:
    - source: salt://dwh/files/etc/nginx/ssl/saiku.key
    - mode: 600
    - user: root
    - group: root
