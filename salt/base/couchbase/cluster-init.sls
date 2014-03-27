{% if grains.couchbase_role is defined and grains.couchbase_role == 'master' %}

cluster_setup:
  cmd:
    - run
    - name: |
        /opt/couchbase/bin/couchbase-cli cluster-init \
        -u {{ pillar['couchbase']['user'] }} \
        -p {{ pillar['couchbase']['password']}} \
        -c 127.0.0.1:{{ pillar['couchbase']['port'] }} \
        --cluster-init-username={{ pillar['couchbase']['user'] }} \
        --cluster-init-password={{ pillar['couchbase']['password'] }} \
        --cluster-init-port={{ pillar['couchbase']['port'] }} \
        --cluster-init-ramsize={{ pillar['couchbase']['ramsize'] }} && sleep 60s
    - unless: |
        /opt/couchbase/bin/couchbase-cli server-list \
        -u {{ pillar['couchbase']['user'] }} \
        -p {{ pillar['couchbase']['password']}} \
        -c 127.0.0.1:{{ pillar['couchbase']['port'] }} | egrep 'healthy active' 
    - require:
      - service: couchbase-server
      - cmd: node-init

{% endif %}