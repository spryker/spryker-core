# This file is maintained by salt
#
# deploy_config.rb

{% set netif = pillar.get('project_network_interface') %}
{% set appservers = mine.get('roles:app', 'network.interfaces', 'grain') %}

{{ netif }}
{{ appservers }}

