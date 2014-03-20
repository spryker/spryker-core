kibana:
  pkg:
    - installed
    - sources:
      - elasticsearch: http://download.elasticsearch.org/logstash/logstash/packages/debian/logstash_1.3.3-1-debian_all.deb
    - require:
      - pkg: requirements
  service:
    - running
    - enable: true
    - watch:
      - file: /etc/elasticsearch/elasticsearch.yml
