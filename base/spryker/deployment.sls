#
# Install spryker deployment system - code and configuration
# Everything is saved in /etc/deploy
#

/etc/deploy:
  file.directory:
    - user: root
    - group: root
    - dir_mode: 755

# Deploy script implementation
/etc/deploy/deploy.rb:
  file.managed:
    - source: salt://spryker/files/etc/deploy/deploy.rb
    - user: root
    - group: root
    - mode: 755
/etc/deploy/functions.rb:
  file.managed:
    - source: salt://spryker/files/etc/deploy/functions.rb
    - user: root
    - group: root
    - mode: 600

# Deploy script configuration
/etc/deploy/config.rb:
  file.managed:
    - source: salt://spryker/files/etc/deploy/config.rb
    - template: jinja
    - user: root
    - group: root
    - mode: 644

# SSH Wrapper and shared private key for deployment.
# It should not be used, ssh AgentForwarding is recommended method.
# Remove ssh_wrapper and deploy.key to use Agent Forwarding
/etc/deploy/ssh_wrapper.sh:
  file.managed:
    - source: salt://spryker/files/etc/deploy/ssh_wrapper.sh
    - user: root
    - group: root
    - mode: 700

/etc/deploy/deploy.key:
  file.managed:
    - source: salt://spryker/files/etc/deploy/deploy.key
    - user: root
    - group: root
    - mode: 400

# SSH key used for deployment. We must be able to ssh as root from deploy host
# to all machines, where we deploy to.
{% if pillar.server_env.ssh.id_rsa is defined %}
/root/.ssh:
  file.directory:
    - mode: 700

/root/.ssh/id_rsa:
  file.managed:
    - user: root
    - group: root
    - mode: 400
    - contents_pillar: server_env:ssh:id_rsa
    - require:
      - file: /root/.ssh

# If authorized_keys is not present (it can be automatically created by salt-cloud)
# then extract public ssh key file from private key file, so that ssh root@localhost will work
extract-root-private-ssh-key:
  cmd.run:
    - name: ssh-keygen -y -f /root/.ssh/id_rsa > /root/.ssh/authorized_keys
    - unless: test -f /root/.ssh/authorized_keys
{% endif %}
