# The default .war file in solr distribution doesn't contain logging classes and configuration
# We have to copy the files from solr and slf4j examples to ClassPath - we use default tomcat classpath directory

copy-jcl-over-slf4j.jar:
  cmd.run:
    - name: cp /data/deploy/download/solr/solr-{{ pillar.solr.version }}/example/lib/ext/jcl-over-slf4j-1.6.6.jar /usr/share/tomcat7/lib/
    - unless: test -f /usr/share/tomcat7/lib/jcl-over-slf4j-1.6.6.jar
    - require:
      - cmd: unpack-solr.tgz

copy-slf4j-api.jar:
  cmd.run:
    - name: cp /data/deploy/download/solr/solr-{{ pillar.solr.version }}/example/lib/ext/slf4j-api-1.6.6.jar /usr/share/tomcat7/lib/
    - unless: test -f /usr/share/tomcat7/lib/slf4j-api-1.6.6.jar
    - require:
      - cmd: unpack-solr.tgz

download-slf4j.zip:
  cmd.run:
    - cwd: /data/deploy/download/solr
    - name: wget -q http://www.slf4j.org/dist/slf4j-1.6.6.zip
    - unless: test -f /data/deploy/download/solr/slf4j-1.6.6.zip
    - require:
      - file: /data/deploy/download/solr

unpack-slf4j.zip:
  cmd.run:
    - cwd: /data/deploy/download/solr
    - require:
      - cmd: download-slf4j.zip
    - name: unzip slf4j-1.6.6.zip
    - unless: test -f /data/deploy/download/solr/slf4j-1.6.6/pom.xml

copy-slf4j-jdk14.jar:
  cmd.run:
    - name: cp /data/deploy/download/solr/slf4j-1.6.6/slf4j-jdk14-1.6.6.jar /usr/share/tomcat7/lib/
    - unless: test -f /usr/share/tomcat7/lib/slf4j-jdk14-1.6.6.jar
    - require:
      - cmd: unpack-slf4j.zip

copy-log4j-over-slf4j.jar:
  cmd.run:
    - name: cp /data/deploy/download/solr/slf4j-1.6.6/log4j-over-slf4j-1.6.6.jar /usr/share/tomcat7/lib/
    - unless: test -f /usr/share/tomcat7/lib/log4j-over-slf4j-1.6.6.jar
    - require:
      - cmd: unpack-slf4j.zip
