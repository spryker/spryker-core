# Check if we run development VM? If so, no salt master is present, so mine mechanism is not available
# We assume that all services run on localhost
{%- if 'dev' in grains.roles %}
{%- set app_hosts = ['localhost'] %}
{%- set web_hosts = ['localhost'] %}
{%- set job_hosts = ['localhost'] %}
{%- set es_data_hosts = ['localhost'] %}
{%- set es_log_hosts = ['localhost'] %}
{%- set cron_master_host = 'localhost' %}
{%- set queue_host = 'localhost' %}
{%- set redis_host = 'localhost' %}
{%- set publish_ip = 'localhost' %}

{%- else %}
# Use mine to fetch IP adresses from minions. Get the IP address of project_interface.
{%- set netif = salt['pillar.get']('hosting:project_network_interface', 'lo') %}

# Get IP's of specific roles from mine.get of running instances
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

{%- set es_data_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:elasticsearch_data', 'network.interfaces', expr_form = 'grain').items() %}
{%- do es_data_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set es_log_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:elasticsearch', 'network.interfaces', expr_form = 'grain').items() %}
{%- do es_log_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set cron_master_host = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address %}
{%- set queue_host = salt['mine.get']('roles:queue', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address %}
{%- set redis_host = salt['mine.get']('roles:redis', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address %}
{%- set publish_ip = grains.ip_interfaces[netif]|first %}
{%- endif %}

# Based on host settings, prepare cluster parameters for elasticsearch
{%- set es_total_nodes = (es_data_hosts)|count %}
{%- set es_minimum_nodes = ( es_total_nodes / 2 )|round|int %}

{%- if es_total_nodes > 1 %}
{%-   set es_replicas = 1 %}
{%-   set es_shards = 6 %}
{%- else %}
{%-   set es_replicas = 0 %}
{%-   set es_shards = 1 %}
{%- endif %}

# Combine settings from above into three directories, which can be easily
# imported from this state
{%- set elasticsearch = {} %}
{%- do elasticsearch.update ({
  'minimum_nodes'        : es_minimum_nodes,
  'total_nodes'          : es_total_nodes,
  'shards'               : es_shards,
  'replicas'             : es_replicas,
}) %}

{%- set host = {} %}
{%- do host.update ({
  'cron_master'          : cron_master_host,
  'queue'                : queue_host,
  'redis'                : redis_host,
}) %}

{%- set hosts = {} %}
{%- do hosts.update ({
  'app'                  : app_hosts,
  'web'                  : web_hosts,
  'job'                  : job_hosts,
  'elasticsearch_data'   : es_data_hosts,
  'elasticsearch_logs'   : es_log_hosts,
}) %}
