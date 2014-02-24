# Rackspace-cloud specific implementation, using 'ufw' as firewall manager
# For AWS EC2 cloud, it can be disabled and the functionality should be leveraged to Security Groups

ufw:
  pkg.installed

# Allow all traffic on private Project Network
ufw allow in on eth2:
  cmd.run:
    - require:
      - pkg: ufw

# Allow traffic from cloud load balancers on shared Service Network
ufw allow from 10.190.254.0/23:
  cmd.run:
    - require:
      - pkg: ufw
ufw allow from 10.189.246.0/23:
  cmd.run:
    - require:
      - pkg: ufw