###
### Load constant definitions
###
{% import_yaml 'settings/port_numbering.sls' as port %}

###
### Parse environments settings
###
{%- set environments = pillar.environments %}
{%- for environment, environment_details in environments.items() %}

# Generate Tomcat ports
{%- do environments[environment]['tomcat'].update ({ 'port_suffix': port['environment'][environment]['port'] + '00' + '7' }) %}

# Generate ActiveMQ ports
{%- do environments[environment].update ({ 'queue': { 'stomp_port': '4' + port['environment'][environment]['port'] + '00' + '6' }}) %}

# Generate Elasticsearch ports
{%- do environments[environment]['elasticsearch'].update ({ 
      'http_port': '1' + port['environment'][environment]['port'] + '00' + '5',
      'transport_port': '2' + port['environment'][environment]['port'] + '00' + '5',
}) %}

###
### Parse store settings
###
{%- for store, store_details in environment_details.stores.items() %}

# Generate Yves/Zed ports
{%- do environments[environment]['stores'][store].yves.update ({ 'port': '1' + port['environment'][environment]['port'] + port['store'][store]['appdomain'] + '0' }) %}
{%- do environments[environment]['stores'][store].zed.update  ({ 'port': '1' + port['environment'][environment]['port'] + port['store'][store]['appdomain'] + '1' }) %}
# Generate DB names
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
