{% set netif = pillar['network']['project_interface'] %}
# This file is maintained by salt
#
# deploy_config.rb


###################
### Directories
###################

# For info only
$project_name = "salt"

# Deployment directory (temporary files)
$deploy_dir = "/data/deploy"

# Destination directory for application
$destination_dir = "/data/shop"   # z.B: /data/shop/<ENV>/current/{Yves,Zed,Solr}

# DocumentRoot for static files
$static_dir = "/data/static"      # z.B: /data/static/<ENV>/

# Username to use to connect to all hosts
$ssh_user = $rsync_user = "root"

# Owner/group of shop files
$www_user = "www-data"
$www_group = "www-data"

# Where to put rev.txt (release info file)
$rev_txt_locations = ['.']

###################
### Stores
###################

# List of stores
$stores = [
   { 'store' => 'DE', 'locale' => 'de_DE', 'appdomain' => '00' },
]

###################
### Hosts
###################

# Hosts that get Yves and Zed application code
# Entries here must be either valid FQDN's or entries in /etc/hosts (prefferably over private network)

## Fixme zed_hosts => app_hosts
{% set app_servers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}
$app_hosts = [
{% for hostname, network_settings in app_servers %}"{{ network_settings[netif]['inet'][0]['address'] }}", {% endfor %}
]

# Host that run cronjobs (use hostname)
{% set cron_servers = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
$jobs_host = [
{% for hostname, network_settings in cron_servers %}"{{ network_settings[netif]['inet'][0]['address'] }}", {% endfor %}
]

# Enable solr indexing?
$use_solr = true

# Host that runs the dwh (use hostname)
{% set dwh_server = salt['mine.get']('roles:dwh', 'network.interfaces', expr_form = 'grain').items()[0] %}
$dwh_host = "{{ dwh_server[netif]['inet'][0]['address'] }}"

# List of environments to deploy
<%- env_rank={"production" => 1, "staging" => 2, "testing" => 3, "development" => -1} -%>
$environments = %w(<%= config['environments'].map{ |k,v| k }.select{ |e| env_rank[e]>-1 }.sort_by{ |e| env_rank[e] }.join(" ")%>)

# Deploy notifications (it's NOT same as Newrelic License Key!)
$newrelic_api_key = "<%= config['monitoring']['newrelic']['api_key'] %>"

###################
### Code repository
###################

$scm_type = "git§"

### Git settings
$ssh_wrapper_path = "/etc/deploy/ssh_wrapper.sh"
$git_path = $deploy_dir + "/git/"
$original_git_url = "{{ pillar.get('git_url') }}"

###################
### Project custom parameters
###################

# Defined here as with default value, must be at least empty hash, can be overwritten in config_local.rb
$project_options = {}

$project_options = [
  { :question => "Use debug mode", :ask_question => false, :options => %w(true), :variable => "debug", :cmdline => "--debug" },
]




{% set app_servers = salt['mine.get']('roles:app', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in app_servers %}
{{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set solr_servers = salt['mine.get']('roles:solr', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_servers %}
solr {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set solr_master = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in solr_master %}
solr_master {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}


{% set cron_servers = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in cron_servers %}
cron {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

{% set dwh_servers = salt['mine.get']('roles:dwh', 'network.interfaces', expr_form = 'grain').items() %}
{% for hostname, network_settings in dwh_servers %}
dwh {{ hostname }} {{ network_settings[netif]['inet'][0]['address'] }}
{% endfor %}

