tomcat7:
  pkg:
    - installed
  service:
    - dead
    - enable: False
    - require:
      - pkg: tomcat7


