requirements:
  pkg.installed:
    - pkgs:
      - openjdk-7-jre

elasticsearch:
  pkg:
    - installed
    - require:
      - pkg: requirements
  service:
    - running
    - enable: true
    - watch:
      - file: /etc/elasticsearch/elasticsearch.yml
    - require:
      - pkg: elasticsearch

/etc/elasticsearch/elasticsearch.yml:
  file:
    - managed
    - template: jinja
    - source: salt://elasticsearch/files/etc/elasticsearch/elasticsearch.yml

{% for shortname, plugin in pillar.get('elasticsearch_plugins', {}).items() %}
/usr/share/elasticsearch/bin/plugin -install {{ plugin.name }} {% if plugin.url is defined %}-url {{ plugin.url }} {%endif%}:
  cmd.run:
    - unless: test -d  /usr/share/elasticsearch/plugins/{{ shortname }}
    - require:
      - pkg: elasticsearch
    - watch_in:
      - service: elasticsearch
{% endfor %}
