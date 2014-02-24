{% if grains.couchbase_role is defined and grains.couchbase_role == 'master' %}
{% set netif = pillar.network.project_interface %}
{% set couchbase_servers = salt['mine.get']('roles:couchbase', 'network.interfaces', expr_form = 'grain').items() %}
{% if couchbase_servers|length > 1 %}

couchbase_cluster_hosts:
  couchbase_cluster.add_host:
    - names: 
{% for hostname, network_settings in couchbase_servers %}
{% if grains.ip_interfaces[netif][0] != network_settings[netif]['inet'][0]['address'] %}
        - {{ network_settings[netif]['inet'][0]['address'] }}
{% endif %}
{% endfor %}
    - server: {{ pillar['couchbase']['host'] }}:{{ pillar['couchbase']['port'] }}
    - user: {{ pillar['couchbase']['user'] }}
    - password: {{ pillar['couchbase']['password'] }}
    - require:
      - service: couchbase-server
{% endif %}
{% endif %}