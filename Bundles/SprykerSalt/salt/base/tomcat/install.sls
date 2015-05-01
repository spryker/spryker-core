tomcat:
  pkg:
    - installed
    - name: tomcat7
    - require:
      - pkg: java
  service:
    - dead
    - name: tomcat7
    - enable: False
    - require:
      - pkg: tomcat

libtcnative-1:
  pkg.installed

