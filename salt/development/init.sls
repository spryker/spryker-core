git-clone:
  cmd.run:
    - name: mkdir -p /data/shop/development/current; export GIT_SSH=/etc/deploy/ssh_wrapper.sh; git clone {{pillar.deploy.git_url}} /data/shop/development/current; chown -R vagrant:vagrant /data/shop/development/current
    - creates: /data/shop/development/current

/data/shop/development/current/config/Shared/config_local.php:
  file.symlink:
    - target: /data/shop/development/shared/config_local.php
    - force: true
    - user: vagrant
    - group: www-data
    - mode: 664

/home/vagrant/.ssh/id_rsa:
  file.managed:
    - source: salt://app/files/deploy/deploy.key
    - user: vagrant
    - group: vagrant
    - mode: 400


{%- for store, store_details in pillar.stores.items() %}
/data/shop/development/current/config/Shared/config_local_{{ store }}.php:
  file.symlink:
    - target: /data/shop/development/shared/config_local_{{ store }}.php
    - force: true
    - user: vagrant
    - group: www-data
    - mode: 664
{%- endfor -%}

# Install Oh-My-Zsh
oh-my-zsh:
  cmd.run:
    - name: cd /home/vagrant; [[ -d /home/vagrant/.oh-my-zsh ]] || sudo -u vagrant git clone git://github.com/robbyrussell/oh-my-zsh.git /home/vagrant/.oh-my-zsh
    - creates: /home/vagrant/.oh-my-zsh

/home/vagrant/.zshrc:
  file.managed:
    - source: salt://development/files/home/vagrant/.zshrc
    - user: vagrant
    - group: vagrant
    - mode: 600

# auto install
#composer-install:
#  cmd.run:
#    - name: cd /data/shop/development/current; /data/shop/development/current/composer.phar --self-update; /data/shop/development/current/composer.phar --dev install; chown -R vagrant:vagrant /data/shop/development/current
#    - creates: /data/shop/development/current/vendor

#{%- for store, store_details in pillar.stores.items() %}
#setup-install-{{store}}:
#  cmd.run:
#    - name: cd /data/shop/development/current;vendor/bin/console setup:install -e development -s {{ store }}
#    - creates: /data/shop/development/current/src/Generated
#{%- endfor -%}
