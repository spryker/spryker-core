requirements:
  pkg.installed:
    - pkgs:
      - openjdk-7-jre

elasticsearch:
  pkg:
    - installed
    - sources:
      - elasticsearch: https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.0.1.deb
    - require:
      - pkg: requirements
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
    - source: salt://elasticsearch/files/etc/elasticsearch/elasticsearch.yml

/etc/default/elasticsearch:
  file.managed:
    - template: jinja
    - source: salt://elasticsearch/files/etc/default/elasticsearch
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
    - source: salt://elasticsearch/files/etc/logrotate.d/elasticsearch
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

