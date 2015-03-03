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
    - require:
      - pkg: elasticsearch-requirements
