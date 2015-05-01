#
# Setup for single instance on default ports (for ELK logging stack).
# No spryker operation data is hold in this cluster.
#
# Takes configuration from pillar.elasticsearch

elk-elasticsearch-requirements:
  pkg.installed:
    - pkgs:
      - openjdk-7-jre

elk-elasticsearch:
  pkg:
    - installed
    - name: elasticsearch
    - require:
      - pkg: elk-elasticsearch-requirements

elk-elasticsearch-service:
  service:
    - name: elasticsearch
    - running
    - enable: true
    - watch:
      - file: /etc/elasticsearch/elasticsearch.yml
      - file: /etc/default/elasticsearch
      - file: /data/elk/elasticsearch
    - require:
      - pkg: elk-elasticsearch

/etc/elasticsearch/elasticsearch.yml:
  file:
    - managed
    - template: jinja
    - source: salt://elk/files/etc/elasticsearch/elasticsearch.yml

/etc/default/elasticsearch:
  file.managed:
    - template: jinja
    - source: salt://elk/files/etc/default/elasticsearch
    - watch_in:
      - service: elk-elasticsearch-service

/data/elk/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 700
    - makedirs: True

/etc/logrotate.d/elasticsearch:
  file.managed:
    - source: salt://elk/files/etc/logrotate.d/elasticsearch

{% for shortname, plugin in pillar.get('elasticsearch.plugins', {}).items() %}
/usr/share/elasticsearch/bin/plugin -install {{ plugin.name }} {% if plugin.url is defined %}-url {{ plugin.url }} {%endif%}:
  cmd.run:
    - unless: test -d  /usr/share/elasticsearch/plugins/{{ shortname }}
    - require:
      - pkg: elasticsearch
    - watch_in:
      - service: elasticsearch-service
{% endfor %}
