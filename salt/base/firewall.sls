# Allow all traffic on private project network
ufw allow in on eth2:
  cmd.run:
    - require:
      - pkg: ufw

ufw:
  pkg.install