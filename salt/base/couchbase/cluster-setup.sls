{% if grains.couchbase_role is defined and grains.couchbase_role == 'master' %}
{% from 'settings/init.sls' import settings with context %}
{% if settings.hosts.couchbase|length > 1 %}

couchbase_cluster_hosts:
  couchbase_cluster.add_host:
    - names: 
{% for host in settings.hosts.couchbase %}
{% if not host in grains.ipv4 %}
        - {{ host }}
{% endif %}
{% endfor %}
    - server: {{ pillar.couchbase.host }}:{{ pillar.couchbase.port }}
    - user: {{ pillar.couchbase.user }}
    - password: {{ pillar.couchbase.password }}
    - require:
      - service: couchbase-server
      - cmd: cluster_setup
{% endif %}
{% endif %}