#
# Parse per-environment settings
#

{% import_yaml 'settings/port_numbering.sls' as port %}


# Parse environments settings
{%- set environments = pillar.environments %}
{%- for environment, environment_details in environments.items() %}

# Generate Jenkins ports
{%- do environments[environment].update ({ 'jenkins': { 'port': '1' + port['environment'][environment]['port'] + '00' + '7' }}) %}

# Generate http static assets ports
{%- do environments[environment].static.update ({ 'port': '1' + port['environment'][environment]['port'] + '00' + '3' }) %}

# Generate Elasticsearch ports
{%- do environments[environment]['elasticsearch'].update ({
      'http_port': '1' + port['environment'][environment]['port'] + '00' + '5',
      'transport_port': '2' + port['environment'][environment]['port'] + '00' + '5',
}) %}

# Generate Redis ports
{%- do environments[environment]['redis'].update ({
      'port': '1' + port['environment'][environment]['port'] + '00' + '9',
}) %}

# Parse store settings
{%- for store, store_details in environment_details.stores.items() %}

# Generate Yves/Zed ports
{%- do environments[environment]['stores'][store].yves.update ({ 'port': '1' + port['environment'][environment]['port'] + port['store'][store]['appdomain'] + '0' }) %}
{%- do environments[environment]['stores'][store].zed.update  ({ 'port': '1' + port['environment'][environment]['port'] + port['store'][store]['appdomain'] + '1' }) %}

# Generate store locale settings
{%- do environments[environment]['stores'][store].update ({ 'locale': port['store'][store]['locale'], 'appdomain': port['store'][store]['appdomain'] }) %}

# Generate SQL database names
{%- do environments[environment]['stores'][store].zed.update({
  'database': {
    'database': store + '_' + environment + '_zed',
    'hostname': environment_details.database.zed.hostname,
    'username': environment_details.database.zed.username,
    'password': environment_details.database.zed.password }
  }) %}
{%- do environments[environment]['stores'][store].update({
  'dump': {
    'database': {
      'database': store + '_' + environment + '_dump',
      'hostname': environment_details.database.zed.hostname,
      'username': environment_details.database.zed.username,
      'password': environment_details.database.zed.password }
    }
  }) %}
{%- endfor %}
{%- endfor %}
