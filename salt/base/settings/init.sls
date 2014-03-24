{% from 'settings/hosts.sls' import host, hosts, publish_ip with context %}
{% from 'settings/environments.sls' import environments with context %}
{% from 'settings/elasticsearch.sls' import elasticsearch with context %}

{%- set settings = {} %}
{%- do settings.update ({
  'environments'         : environments,
  'host'                 : host,
  'hosts'                : hosts,
  'publish_ip'           : publish_ip,
  'elasticsearch'        : elasticsearch,
}) %}


