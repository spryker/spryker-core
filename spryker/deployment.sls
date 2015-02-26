#
# Install spryker deployment system - code and configuration
# Everything is saved in /etc/deploy
#

/etc/deploy:
  file.directory:
    - user: root
    - group: root
    - dir_mode: 755

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

/etc/deploy/config.rb:
  file.managed:
    - source: salt://spryker/files/etc/deploy/config.rb
    - template: jinja
    - user: root
    - group: root
    - mode: 644

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
{% endif %}
