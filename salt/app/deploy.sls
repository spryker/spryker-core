/etc/deploy:
  file.directory:
    - user: root
    - group: root
    - dir_mode: 755

#/etc/deploy/deploy.rb:
#/etc/deploy/functions.rb:

/etc/deploy/config.rb:
  file.managed:
    - source: salt://app/files/deploy_config.rb
    - template: jinja
            
#/etc/deploy/ssh_wrapper:
#/etc/deploy/deploy.key: