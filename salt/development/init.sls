sudo -u vagrant ssh-keyscan -H {{ pillar.deploy.git_hostname }} >> /home/vagrant/.ssh/known_hosts:
  cmd.run:
    - creates: /data/shop/development/current

sudo -u vagrant git clone {{pillar.deploy.git_url}} /home/vagrant/dev:
  cmd.run:
    - creates: /data/shop/development/current
