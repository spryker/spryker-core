#
# Rackspace-specific hoster packages (monitoring and backup)
#

# Firewall: UFW package
ufw:
  pkg.installed

# Networking configuration: nothing to do

# Disk drives: if machines have grains with list of filesystems, those will be prepared by the included state
include:
  - .filesystem


# Monitoring
rackspace-monitoring:
  pkgrepo.managed:
    - humanname: Rackspace monitoring tools
    - name: deb http://stable.packages.cloudmonitoring.rackspace.com/debian-wheezy-x86_64 cloudmonitoring main
    - file: /etc/apt/sources.list.d/rackspace-monitoring.list
    - key_url: https://monitoring.api.rackspacecloud.com/pki/agent/linux.asc
    - require_in:
      - pkg: rackspace-monitoring-agent

rackspace-monitoring-agent:
  pkg.installed

setup-rackspace-monitoring-agent:
  cmd.run:
    - name: rackspace-monitoring-agent --setup --username {{ pillar.rackspace.username }} --apikey {{ pillar.rackspace.apikey }} && service rackspace-monitoring-agent restart
    - unless: test -f /etc/rackspace-monitoring-agent.cfg
    - requires:
      - pkg: rackspace-monitoring-agent

# Backup
rackspace-backup:
  pkgrepo.managed:
    - humanname: Rackspace backup agent
    - name: deb [arch=amd64] http://agentrepo.drivesrvr.com/debian/ serveragent main
    - file: /etc/apt/sources.list.d/rackspace-backup.list
    - key_url: http://agentrepo.drivesrvr.com/debian/agentrepo.key
    - require_in:
      - pkg: driveclient

driveclient:
  pkg.installed

setup-rackspace-backup-agent:
  cmd.run:
    - name: /usr/local/bin/driveclient --configure -u {{ pillar.rackspace.username }} -k {{ pillar.rackspace.apikey }} -t LON && service driveclient restart && update-rc.d driveclient defaults
    - unless: test -f /var/run/driveclient.pid
    - requires:
      - pkg: driveclient

# Support access
/etc/sudoers.d/rackspace-support:
  file.managed:
    - source: salt://hosting/files/rackspace/etc/sudoers.d/rackspace-support
