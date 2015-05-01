# Rackspace-cloud specific implementation, using 'ufw' as firewall manager
# For AWS EC2 cloud, it can be disabled and the functionality should be leveraged to Security Groups

ufw:
  pkg.installed

# Allow all traffic on private Project Network
ufw allow in on eth2:
  cmd.run:
    - unless: ufw status | grep 'Anywhere on eth2 *ALLOW *Anywhere'
    - require:
      - pkg: ufw

# Allow traffic from cloud load balancers on shared Service Network
# The IP's here are valid for Rackspace Cloud - London and have been provided by support
ufw allow from 10.190.254.0/23:
  cmd.run:
    - unless: ufw status | grep 'Anywhere *ALLOW *10.190.254.0/23'
    - require:
      - pkg: ufw
ufw allow from 10.189.246.0/23:
  cmd.run:
    - unless: ufw status | grep 'Anywhere *ALLOW *10.189.246.0/23'
    - require:
      - pkg: ufw

{% if 'dwh_saiku' in grains.roles %}
ufw allow 443/tcp:
  cmd.run:
    - unless: ufw status | grep '443/tcp *ALLOW *Anywhere'
    - require:
      - pkg: ufw
{% endif %}