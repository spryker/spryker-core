{% import_yaml 'settings/port_numbering.sls' as port %}
{% include 'settings/hosts.sls' %}
{% include 'settings/environments.sls' %}
{% include 'settings/elasticsearch.sls' %}


{%- set settings = {} %}
{%- do settings.update ({
  'environments'         : environments,
  'host'                 : host,
  'hosts'                : hosts,
  'publish_ip'           : publish_ip,
  'elasticsearch'        : elasticsearch,
}) %}


