# The default .war file in solr distribution doesn't contain logging classes and configuration
# We have to copy the files from solr examples to ClassPath - we use default tomcat classpath directory

copy-solr-logging-jars:
  cmd.run:
    - name: cp /data/deploy/download/solr/solr-{{ pillar.solr.version }}/example/lib/ext/*.jar /usr/share/tomcat7/lib/
    - unless: find /usr/share/tomcat7/lib/ -name slf4j-log4j* -print -quit | grep jar
    - require:
      - cmd: unpack-solr.tgz

copy-solr-logging-config:
  file.copy:
    - source: /data/deploy/download/solr/solr-{{ pillar.solr.version }}/example/resources/log4j.properties
    - name: /usr/share/tomcat7/lib/log4j.properties
    - require:
      - cmd: unpack-solr.tgz