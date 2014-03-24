# Setup for multiple environments
{%- for environment, environment_details in pillar.environments.items() %}

/tmp/es-{{ environment }}:
  file.managed

/data/shop/{{ environment }}/shared/elasticsearch:
  file.directory:
    - user: elasticsearch
    - group: elasticsearch
    - mode: 700
    - requires:
      - file: /data/shop/{{ environment }}/shared

{%- endfor %}