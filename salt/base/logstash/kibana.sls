/etc/nginx/ssl/kibana.crt:
  file.managed:
    - source: salt://logstash/files/etc/nginx/ssl/kibana.crt
    - mode: 600
    - user: root
    - group: root
    - require:
      - file: /etc/nginx/ssl

/etc/nginx/ssl/kibana.key:
  file.managed:
    - source: salt://logstash/files/etc/nginx/ssl/kibana.key
    - mode: 600
    - user: root
    - group: root
    - require:
      - file: /etc/nginx/ssl

# The default password for dwh (cubes)
/etc/nginx/htpasswd-kibana:
  file.managed:
    - source: salt://logstash/files/etc/nginx/htpasswd-kibana
    - user: www-data
    - group: www-data
    - mode: 640


/etc/nginx/sites-available/kibana.conf:
  file.managed:
    - source: salt://logstash/files/etc/nginx/sites-available/kibana.conf
    - template: jinja
    - user: root
    - group: root
    - mode: 644
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/kibana.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/kibana.conf
    - force: true
    - require:
      - file: /etc/nginx/sites-available/kibana.conf
    - watch_in:
      - service: nginx
