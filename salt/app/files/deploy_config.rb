# This file is maintained by salt
#
# deploy_config.rb

{% set netif = pillar.get('project_network_interface') %}
git {{ pillar.get('git_url') }}

{% set app_servers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in app_servers %}
{{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set solr_servers = salt['mine.get']('roles:solr', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_servers %}
solr {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set solr_master = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_servers %}
solr_master {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}


{% set cron_servers = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_servers %}
cron {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set dwh_servers = salt['mine.get']('roles:dwh', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_servers %}
dwh {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}
