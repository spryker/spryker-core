{%- for environment, environment_details in pillar.environments.items() %}
#
# Directories for tomcat instance
#
/data/shop/{{ environment }}/shared/tomcat:
  file.recurse:
    - source: salt://tomcat/files/tomcat_home
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true

/data/logs/{{ environment }}/tomcat:
  file.directory:
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true

/data/shop/{{ environment }}/shared/tomcat/logs:
  file.symlink:
    - target: /data/logs/{{ environment }}/tomcat
    - force: true
    - require:
      - file: /data/logs/{{ environment }}/tomcat
      - file: /data/shop/{{ environment }}/shared/tomcat

/data/shop/{{ environment }}/shared/tomcat/bin:
  file.symlink:
    - target: /usr/share/tomcat7/bin
    - force: true
    - require:
      - file: /data/shop/{{ environment }}/shared/tomcat
      - pkg: tomcat

/data/shop/{{ environment }}/shared/tomcat/lib:
  file.symlink:
    - target: /usr/share/tomcat7/lib
    - force: true
    - require:
      - file: /data/shop/{{ environment }}/shared/tomcat
      - pkg: tomcat

#
# Tomcat config
#
/data/shop/{{ environment }}/shared/tomcat/conf/server.xml:
  file.managed:
    - source: salt://tomcat/files/conf/server.xml
    - template: jinja
    - user: www-data
    - group: www-data
    - mode: 640
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}




{%- endfor %}

