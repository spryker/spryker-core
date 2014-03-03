sudo -u vagrant ssh-keyscan -H {{ pillar.deploy.git_hostname }} >> /home/vagrant/.ssh/known_hosts:
  cmd.run:
    - creates: /data/shop/development/current

git clone {{pillar.deploy.git_url}} /data/shop/development/current:
  cmd.run:
    - creates: /data/shop/development/current
