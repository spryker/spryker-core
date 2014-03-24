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

{%- set solr_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:solr', 'network.interfaces', expr_form = 'grain').items() %}
{%- do solr_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set job_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
{%- do job_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set dwh_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:dwh', 'network.interfaces', expr_form = 'grain').items() %}
{%- do dwh_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}

{%- set es_data_hosts = [] %}
{%- for hostname, network_settings in salt['mine.get']('roles:elasticsearch_data', 'network.interfaces', expr_form = 'grain').items() %}
{%- do es_data_hosts.append(network_settings[netif]['inet'][0]['address']) %}
{% endfor %}


{%- set host = {} %}
{%- do host.update ({
  'solr_master'          : salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address,
  'cron_master'          : salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address,
  'queue'                : salt['mine.get']('roles:queue', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address,
}) %}

{%- set hosts = {} %}
{%- do hosts.update ({  
  'couchbase'            : couchbase_hosts,
  'app'                  : app_hosts,
  'web'                  : web_hosts,
  'solr'                 : solr_hosts,
  'job'                  : job_hosts,
  'dwh'                  : dwh_hosts,
  'elasticsearch_data'   : es_data_hosts,
}) %}

{%- set publish_ip = grains.ip_interfaces[netif]|first }