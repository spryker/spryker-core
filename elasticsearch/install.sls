#
# Install Elasticsearch
#

elasticsearch-requirements:
  pkg.installed:
    - pkgs:
      - openjdk-7-jre

elasticsearch:
  pkg:
    - installed
    - sources:
      - elasticsearch: https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.4.4.deb
    - require:
      - pkg: elasticsearch-requirements
