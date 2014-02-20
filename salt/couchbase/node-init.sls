{{ pillar['couchbase']['data_path'] }}:
  file.directory:
    - makedirs: true
    - user: couchbase
    - group: couchbase
    - dir_mode: 700

node-init:
  cmd:
    - run
    - name: |
        /opt/couchbase/bin/couchbase-cli node-init \
        -u {{ pillar['couchbase']['user'] }} \
        -p {{ pillar['couchbase']['password']}} \
        --node-init-data-path={{ pillar['couchbase']['data_path'] }}
        -c 127.0.0.1:8091
    - unless: |
        /opt/couchbase/bin/couchbase-cli server-list \
        -u {{ pillar['couchbase']['user'] }} \
        -p {{ pillar['couchbase']['password']}} \
        -c 127.0.0.1:{{ pillar['couchbase']['port'] }} | egrep 'healthy active' 
    - require:
      - service: couchbase-server