{%- set netif = pillar.network.project_interface -%}
{%- set solr_master = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address -%}
{%- for environment, environment_details in pillar.environments.items() %}

/data/shop/{{ environment }}/shared/data/common/solr:
  file.directory:
    - mode: 755
    - user: www-data
    - group: www-data
    - makedirs: True

/data/shop/{{ environment }}/shared/data/common/solr/replication.xml:
  file.managed:
    - mode: 644
    - user: www-data
    - group: www-data
    - source: salt://tomcat/files/solr/replication-{{ grains.solr_role }}.xml
    - template: jinja
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common/solr
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}
      solr_master: {{ solr_master }}

/data/shop/{{ environment }}/shared/data/common/solr/solr.xml:
  file.managed:
    - mode: 644
    - user: www-data
    - group: www-data
    - source: salt://tomcat/files/solr/solr.xml
    - replace: False
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common/solr

/data/shop/{{ environment }}/shared/tomcat/conf/Catalina/localhost/solr.xml:
  file.managed:
    - mode: 644
    - user: www-data
    - group: www-data
    - source: salt://tomcat/files/solr/context.xml
    - template: jinja
    - context:
      environment: {{ environment }}

/data/shop/{{ environment }}/shared/tomcat/webapps/solr.war:
  file.copy:
    - target: /data/deploy/download/solr-{{ pillar.solr.version }}.war
    - require:
      - file: /data/deploy/download/solr-{{ pillar.solr.version }}.war
      - file: /data/shop/{{ environment }}/shared/tomcat/conf/Catalina/localhost/solr.xml
      - file: /data/shop/{{ environment }}/shared/data/common/solr/replication.xml
      - file: /data/shop/{{ environment }}/shared/data/common/solr/solr.xml
    - watch_in:
      - service: tomcat7-{{ environment }}

{%- endfor %}