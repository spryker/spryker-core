{%- for environment, environment_details in pillar.environments.items() %}
/data/shop/{{ environment }}/shared/Generated:
  file.directory:
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true
    - recurse:
      - user
      - group
      - mode

/data/shop/{{ environment }}/shared/data/common:
  file.directory:
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true
    - recurse:
      - user
      - group
      - mode

/data/logs/{{ environment }}:
  file.directory:
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true
    - recurse:
      - user
      - group
      - mode

/data/storage/{{ environment }}/static:
  file.directory:
    - user: www-data
    - group: www-data
    - dir_mode: 755
    - file_mode: 755
    - makedirs: true
    - recurse:
      - user
      - group
      - mode

/data/shop/{{ environment }}/shared/data/static:
  file.symlink:
    - target: /data/storage/{{ environment }}/static
    - force: true
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common
      - file: /data/storage/{{ environment }}/static

/data/shop/{{ environment }}/shared/config_local.php:
  file.managed:
    - source: salt://app/files/config/config_local.php
    - template: jinja
    - user: www-data
    - group: www-data
    - mode: 640
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common
    - context:
      environment: {{ environment }}
      environment_details: x
#      environment_details: {{ environment_details }}


{%- endfor %}

