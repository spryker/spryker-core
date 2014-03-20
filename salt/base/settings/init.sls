{% from 'settings/hosts.sls' import host, hosts with context %}
{% from 'settings/environments.sls' import environments with context %}

{%- set settings = {} %}
{%- do settings.update ({
  'environments'         : environments,
  'host'                 : host,
  'hosts'                : hosts,
}) %}


