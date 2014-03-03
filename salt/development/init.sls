mkdir -p /data/shop/development/current; export GIT_SSH=/etc/deploy/ssh_wrapper.sh; git clone {{pillar.deploy.git_url}} /data/shop/development/current; chown -R vagrant /data/shop/development/current; chgrp -R vagrant /data/shop/development/current:
  cmd.run:
    - creates: /data/shop/development/current

cd /data/shop/development/current; /data/shop/development/current/composer.phar --self-update; /data/shop/development/current/composer.phar --dev install; chown -R vagrant /data/shop/development/current; chgrp -R /data/shop/development/current:
  cmd.run:
    - creates: /data/shop/development/current/vendor
