ufw:
  pkg.installed

# Allow all traffic on private project network
ufw allow in on eth2:
  cmd.run:
    - require:
      - pkg: ufw

# Allow traffic from cloud load balancers
ufw allow from 10.190.254.0/23:
  cmd.run:
    - require:
      - pkg: ufw
ufw allow from 10.189.246.0/23:
  cmd.run:
    - require:
      - pkg: ufw