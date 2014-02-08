# This file is maintained by salt
#
# deploy_config.rb

{% set netif = pillar.get('project_network_interface') %}
git {{ pillar.get('git_url') }}
{% set appservers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}

{{ netif }}
{{ appservers }}


{% for hostname, network_settings in appservers %}
Host: {{ hostname }}
{{ network_settings[netif] }}
{{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}
