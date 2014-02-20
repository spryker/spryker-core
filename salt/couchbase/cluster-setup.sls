{% if grains.data_role == 'master' %}
{% set netif = pillar.network.project_interface %}
{% set couchbase_servers = salt['mine.get']('roles:couchbase', 'network.interfaces', expr_form = 'grain').items() %}

couchbase_cluster_hosts:
  couchbase_cluster.add_host:
    - names: 
{% for hostname, network_settings in couchbase_servers %}
        - {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}
    - server: {{ pillar['couchbase']['host'] }}:{{ pillar['couchbase']['port'] }}
    - user: {{ pillar['couchbase']['user'] }}
    - password: {{ pillar['couchbase']['password'] }}

{% endif %}