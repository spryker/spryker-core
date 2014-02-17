/etc/deploy:
  file.directory:
    - user: root
    - group: root
    - dir_mode: 755

/etc/deploy/deploy.rb:
  file.managed:
    - source: salt://app/files/deploy/deploy.rb
    - user: root
    - group: root
    - mode: 700


/etc/deploy/functions.rb:
  file.managed:
    - source: salt://app/files/deploy/functions.rb
    - user: root
    - group: root
    - mode: 600

/etc/deploy/config.rb:
  file.managed:
    - source: salt://app/files/deploy/deploy_config.rb
    - template: jinja
    - user: root
    - group: root
    - mode: 644

/etc/deploy/ssh_wrapper.sh:
  file.managed:
    - source: salt://app/files/deploy/ssh_wrapper.sh
    - user: root
    - group: root
    - mode: 755

/etc/deploy/deploy.key:
  file.managed:
    - source: salt://app/files/deploy/deploy.key
    - user: root
    - group: root
    - mode: 400

# Use this to copy /root/.ssh/id_rsa from Salt Master to Salt Minions
{{ if server_env.ssh.id_rsa is defined }}
/root/.ssh/id_rsa:
  file.managed:
    - user: root
    - group: root
    - mode: 400
    - contents_pillar: server_env:ssh:id_rsa
{{ endif }}
