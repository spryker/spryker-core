#
# Provider-specific configuration for hoster: Vagrant
#
# Vagrant actually does not need any hoster-specific configuration.
# This file can be used as an empty placeholder for creating another hoster
# configurations.

# Firewall: we don't use it, but let's install UFW package
ufw:
  pkg.installed

# Networking configuration: setup /etc/hosts, dns configuration
/etc/resolv.conf:
  file.managed:
    - source: salt://hosting/files/vagrant/etc/resolv.conf

/etc/hosts:
  file.managed:
    - source: salt://hosting/files/vagrant/etc/hosts

# Disk drives: nothing to do, we're just using easy vagrant setup

# Monitoring: nothing to do

# Backup: nothing to do
