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
    - require:
      - pkg: elasticsearch
