#
# Provider-specific configuration for hoster: simple
#
# This provider provide a basic setup for a single machine setup which comes wit a pre configured image as provided by
# managed servers

include:
  - .firewall

# Networking configuration: setup /etc/hosts, dns configuration
/etc/resolv.conf:
  file.managed:
    - source: salt://hosting/files/simple/etc/resolv.conf

{% from 'settings/init.sls' import settings with context %}
/etc/hosts:
  file.managed:
    - source: salt://hosting/files/simple/etc/hosts
    - template: jinja
    - context:
      hosts: {{ hosts }}

# Monitoring: nothing to do

# Backup: nothing to do
