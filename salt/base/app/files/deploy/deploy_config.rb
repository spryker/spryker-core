{%- set netif = pillar.network.project_interface %}
{%- set app_servers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}
{%- set web_servers = salt['mine.get']('roles:web', 'network.interfaces', expr_form = 'grain').items() %}
{%- set solr_servers = salt['mine.get']('roles:solr', 'network.interfaces', expr_form = 'grain').items() %}
{%- set solr_masters = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items() %}
{%- set cron_servers = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
{%- set dwh_servers = salt['mine.get']('roles:dwh', 'network.interfaces', expr_form = 'grain').items() %}
{%- set has_dwh = (dwh_servers|count > 0) -%}
# This file is maintained by salt
# deploy_config.rb


###################
### Locations and permissions
###################

# For info only
$project_name = "salt"

# Deployment directory (temporary files)
$deploy_dir = "/data/deploy"

# Destination directory for application
$destination_dir = "/data/shop"

# Username to use to connect to all hosts
$ssh_user = $rsync_user = "root"

# Owner/group of shop files
$www_user = "www-data"
$www_group = "www-data"

# Where to put rev.txt (release info file)
$rev_txt_locations = ['.']

###################
### Environments, stores
###################

# List of application environments
$environments = [
{%- for environment, environment_details in pillar.environments.items() %}
  "{{ environment }}",
{%- endfor %}
]

# List of stores
$stores = [
{%- for store, store_details in pillar.stores.items() %}
   { 'store' => '{{ store }}', 'locale' => '{{ store_details.locale }}', 'appdomain' => '{{ store_details.appdomain }}' },
{%- endfor %}
]

###################
### Hosts and roles
###################

# Enable solr indexing?
$use_solr = true

# Enable data warehouse?
$use_dwh = {{ has_dwh|lower }}

# Hosts that have the application code
$app_hosts = [
{% for hostname, network_settings in app_servers %}  "{{ network_settings[netif]['inet'][0]['address'] }}",
{% endfor %}
]

# Hosts that run web server
$web_hosts = [
{% for hostname, network_settings in web_servers %}  "{{ network_settings[netif]['inet'][0]['address'] }}",
{% endfor %}
]

# Host(s) that run jobs
$jobs_hosts = [
{% for hostname, network_settings in cron_servers %}  "{{ network_settings[netif]['inet'][0]['address'] }}",
{% endfor %}
]

# Host(s) that run solr
$solr_hosts = [
{% for hostname, network_settings in solr_servers %}  "{{ network_settings[netif]['inet'][0]['address'] }}", 
{% endfor %}
]

# Host that runs the dwh
{%- if has_dwh %}
$dwh_host = "{{ dwh_servers[0][netif]['inet'][0]['address'] }}"
{% endif %}

# Deploy notifications (API key - it's NOT same as Newrelic License Key!)
$newrelic_api_key = "{{ pillar.newrelic.api_key|default('', true) }}"

###################
### Git code repository
###################

$scm_type = "git"
$ssh_wrapper_path = "/etc/deploy/ssh_wrapper.sh"
$git_path = $deploy_dir + "/git/"
$original_git_url = "{{ pillar.deploy.git_url }}"

###################
### Project custom parameters
###################

# Defined here as with default value, must be at least empty hash, can be overwritten in config_local.rb
$project_options = {}

$project_options = [
  { :question => "Use debug mode", :ask_question => false, :options => %w(true), :variable => "debug", :cmdline => "--debug" },
]

