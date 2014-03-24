{%- from 'settings/hosts.sls' import hosts with context %}

{%- set total_nodes = hosts.elasticsearch_data|count %}
{%- set minumum_nodes = 2 %}

{%- if total_nodes > 1 %}
{%-   set replicas = 1 %}
{%-   set shards = 6 %}
{%- else %}
{%-   set replicas = 0 %}
{%-   set shards = 1 %}
{%- endif %}



{%- set elasticsearch = {} %}
{%- do elasticsearch.update ({
  'total_nodes'          : total_nodes,
  'minimum_nodes'        : minimum_nodes,
  'shards'               : shards,
  'replicas'             : replicas,
  'autodiscovery'        : autodiscovery,
}) %}
