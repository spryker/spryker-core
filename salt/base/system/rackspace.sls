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