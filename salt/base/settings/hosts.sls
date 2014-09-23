# FIXME:
# in mine.get we should change 'roles:xxx', 'network.interfaces', expr_form = 'grain' to something like:
# 'G@roles:xxx AND G@deployment:' + env -  so that on prod we only select here minions from prod env, etc.
# see:
# http://salt.readthedocs.org/en/latest/ref/states/vars.html   (env variable)
# http://docs.saltstack.com/en/latest/topics/targeting/compound.html - compound matches
### Easy settings copy
{%- set netif = pillar.network.project_interface %}

### Get IP's of specific roles from mine.get of running instances
{%- set couchbase_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:couchbase', 'network.interfaces', expr_form = 'grain').items() %}
{%- do couchbase_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set app_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}
{%- do app_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set web_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:web', 'network.interfaces', expr_form = 'grain').items() %}
{%- do web_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set job_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
{%- do job_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set dwh_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:dwh_jobs', 'network.interfaces', expr_form = 'grain').items() %}
{%- do dwh_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set es_data_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:elasticsearch_data', 'network.interfaces', expr_form = 'grain').items() %}
{%- do es_data_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set es_log_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:elasticsearch', 'network.interfaces', expr_form = 'grain').items() %}
{%- do es_log_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set dwh_db_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:dwh_database', 'network.interfaces', expr_form = 'grain').items() %}
{%- do dwh_db_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

### Based on host info, prepare numbers for elasticsearch
{%- set es_total_nodes = (es_data_hosts)|count %}
{%- set es_minimum_nodes = ( es_total_nodes / 2 )|round|int %}

{%- if es_total_nodes > 1 %}
{%-   set es_replicas = 1 %}
{%-   set es_shards = 6 %}
{%- else %}
{%-   set es_replicas = 0 %}
{%-   set es_shards = 1 %}
{%- endif %}

{%- set elasticsearch = {} %}
{%- do elasticsearch.update ({
  'minimum_nodes'        : es_minimum_nodes,
  'total_nodes'          : es_total_nodes,
  'shards'               : es_shards,
  'replicas'             : es_replicas,
}) %}

{%- set host = {} %}
{%- do host.update ({
  'cron_master'          : salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address,
  'queue'                : salt['mine.get']('roles:queue', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address,
}) %}

{%- set hosts = {} %}
{%- do hosts.update ({  
  'couchbase'            : couchbase_hosts,
  'app'                  : app_hosts,
  'web'                  : web_hosts,
  'job'                  : job_hosts,
  'dwh'                  : dwh_hosts,
  'dwh_database'         : dwh_db_hosts,
  'elasticsearch_data'   : es_data_hosts,
  'elasticsearch_logs'   : es_log_hosts,
}) %}

{%- set publish_ip = grains.ip_interfaces[netif]|first %}
