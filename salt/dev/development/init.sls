/home/vagrant/.ssh/id_rsa:
  file.managed:
    - source: salt://app/files/deploy/deploy.key
    - user: vagrant
    - group: vagrant
    - mode: 400
  cmd.run:
    - name: ssh-keyscan -H {{ pillar.deploy.git_hostname }} >> /home/vagrant/.ssh/known_hosts
    - unless: test -f /home/vagrant/.ssh/id_rsa

/home/vagrant/.ssh/known_hosts:
  file.managed:
    - user: vagrant
    - group: vagrant
    - mode: 644

# Install Oh-My-Zsh
oh-my-zsh:
  cmd.run:
    - name: cd /home/vagrant; [[ -d /home/vagrant/.oh-my-zsh ]] || sudo -u vagrant git clone git://github.com/robbyrussell/oh-my-zsh.git /home/vagrant/.oh-my-zsh
    - unless: test -f /home/vagrant/.oh-my-zsh

/home/vagrant/.zshrc:
  file.managed:
    - source: salt://development/files/home/vagrant/.zshrc
    - user: vagrant
    - group: vagrant
    - mode: 600

# install grunt
grunt-install:
  cmd.run:
    - name: npm install -g grunt-cli
    - unless: grunt --version

# restart nginx
restart-nginx:
  cmd.run:
    - name: /etc/init.d/nginx restart

/etc/resolv.conf:
  file.managed:
    - source: salt://development/files/etc/resolv.conf
