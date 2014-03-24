# Setup for single instance on default ports (i.e. for logging or shared ES cluster for environments)

elasticsearch:
  service:
    - running
    - enable: true
    - watch:
      - file: /etc/elasticsearch/elasticsearch.yml
      - file: /etc/default/elasticsearch
      - file: /data/elasticsearch
    - require:
      - pkg: elasticsearch

/etc/elasticsearch/elasticsearch.yml:
  file:
    - managed
    - template: jinja
    - source: salt://elasticsearch/files/single/etc/elasticsearch/elasticsearch.yml

/etc/default/elasticsearch:
  file.managed:
    - template: jinja
    - source: salt://elasticsearch/files/single/etc/default/elasticsearch
    - watch_in:
      - service: elasticsearch

/data/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 700
    - requires:
      - file: /data

/etc/logrotate.d/elasticsearch:
  file.managed:
    - source: salt://elasticsearch/files/single/etc/logrotate.d/elasticsearch
    - require:
      - service: elasticsearch


{% for shortname, plugin in pillar.get('elasticsearch.plugins', {}).items() %}
/usr/share/elasticsearch/bin/plugin -install {{ plugin.name }} {% if plugin.url is defined %}-url {{ plugin.url }} {%endif%}:
  cmd.run:
    - unless: test -d  /usr/share/elasticsearch/plugins/{{ shortname }}
    - require:
      - pkg: elasticsearch
    - watch_in:
      - service: elasticsearch
{% endfor %}

