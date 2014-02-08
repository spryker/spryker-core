# This file is maintained by salt
#
# deploy_config.rb

{% set netif = pillar.get('project_network_interface') %}
{% set appservers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain') %}

{{ netif }}
{{ appservers }}

