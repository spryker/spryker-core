#
# Macro: Setup one Elasticsearch instance
#

{% macro elasticsearch_instance(environment, environment_details, settings) -%}

# Data directory
/data/shop/{{ environment }}/shared/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 700
    - requires:
      - file: /data/shop/{{ environment }}/shared

# Log directory
/data/logs/{{ environment }}/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 755

# Service configuration
/etc/default/elasticsearch-{{ environment }}:
  file.managed:
    - source: salt://elasticsearch/files/elasticsearch_instance/etc/default/elasticsearch
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
      settings: {{ settings }}
    - watch_in:
      - service: elasticsearch-{{ environment }}

# Service init script
/etc/init.d/elasticsearch-{{ environment }}:
  file.managed:
    - source: salt://elasticsearch/files/elasticsearch_instance/etc/init.d/elasticsearch
    - mode: 755
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}

# Configuration directory
/etc/elasticsearch-{{ environment }}:
  file.directory:
    - user: root
    - group: root
    - mode: 755

# Configuration - main yaml file
/etc/elasticsearch-{{ environment }}/elasticsearch.yml:
  file.managed:
    - source: salt://elasticsearch/files/elasticsearch_instance/etc/elasticsearch/elasticsearch.yml
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}
      settings: {{ settings }}
    - watch_in:
      - service: elasticsearch-{{ environment }}


# Configuration - logging yaml file
/etc/elasticsearch-{{ environment }}/logging.yml:
  file.managed:
    - source: salt://elasticsearch/files/elasticsearch_instance/etc/elasticsearch/logging.yml
    - mode: 644
    - user: root
    - group: root
    - template: jinja
    - context:
      environment: {{ environment }}
    - watch_in:
      - service: elasticsearch-{{ environment }}

# Setvice
elasticsearch-{{ environment }}:
  service:
    - running
    - enable: True
    - require:
      - pkg: elasticsearch
      - file: /etc/init.d/elasticsearch-{{ environment }}
      - file: /data/shop/{{ environment }}/shared/elasticsearch
      - file: /data/logs/{{ environment }}/elasticsearch

# Symlink for easier location of ES configs
/etc/elasticsearch/{{ environment }}:
  file.symlink:
    - target: /etc/elasticsearch-{{ environment }}

{%- endmacro %}
