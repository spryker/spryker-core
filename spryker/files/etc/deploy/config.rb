{% from 'settings/init.sls' import settings with context %}
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
{%- for environment, environment_details in pillar.environments.items() %}{%- if loop.first %}
{%- for store in pillar.stores %}
   { 'store' => '{{ store }}', 'locale' => '{{ settings.environments[environment].stores }}', 'appdomain' => '{{ settings.environments[environment].stores }}' },
{%- endfor %}
{%- endif %}{%- endfor %}
]

###################
### Hosts and roles
###################

# Enable data warehouse?
$use_dwh = false

# Hosts that have the application code
$app_hosts = [
{% for host in settings.hosts.app %}  "{{ host }}",
{% endfor %}
]

# Hosts that run web server
$web_hosts = [
{% for host in settings.hosts.web %}  "{{ host }}",
{% endfor %}
]

# Host(s) that run jobs
$jobs_hosts = [
{% for host in settings.hosts.job %}  "{{ host }}",
{% endfor %}
]

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

$project_options = [
  { :question => "Use debug mode", :ask_question => false, :options => %w(true), :variable => "debug", :cmdline => "--debug" },
]
