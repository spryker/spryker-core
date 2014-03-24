# Setup for multiple environments
# This implementation is Yves&Zed specific and it takes data from Yves&Zed pillars

include:
  - .install

/etc/logrotate.d/elasticsearch-environments:
  file.managed:
    - source: salt://elasticsearch/files/environments/etc/logrotate.d/elasticsearch-environments

{%- for environment, environment_details in pillar.environments.items() %}
/data/shop/{{ environment }}/shared/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 700
    - requires:
      - file: /data/shop/{{ environment }}/shared

/data/logs/{{ environment }}/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 755

/etc/default/elasticsearch-{{ environment }}:
  file.managed:
    - source: salt://elasticsearch/files/environments/etc/default/elasticsearch
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
    - watch_in:
      - service: elasticsearch-{{ environment }}

/etc/init.d/elasticsearch-{{ environment }}:
  file.managed:
    - source: salt://elasticsearch/files/environments/etc/init.d/elasticsearch
    - mode: 755
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}


/etc/elasticsearch-{{ environment }}:
  file.directory:
    - user: root
    - group: root
    - mode: 755

/etc/elasticsearch-{{ environment }}/elasticsearch.yml:
  file.managed:
    - source: salt://elasticsearch/files/environments/etc/elasticsearch/elasticsearch.yml
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
    - watch_in:
      - service: elasticsearch-{{ environment }}

/etc/elasticsearch-{{ environment }}/logging.yml:
  file.managed:
    - source: salt://elasticsearch/files/environments/etc/elasticsearch/logging.yml
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
    - watch_in:
      - service: elasticsearch-{{ environment }}

elasticsearch-{{ environment }}:
  service:
    - running
    - enable: true
    - require:
      - pkg: elasticsearch
      - file: /etc/init.d/elasticsearch-{{ environment }}
      - file: /data/shop/{{ environment }}/shared/elasticsearch
      - file: /data/logs/{{ environment }}/elasticsearch

# For easier location of ES configs
/etc/elasticsearch/{{ environment }}:
  file.symlink:
    - target: /etc/elasticsearch-{{ environment }}

{%- endfor %}