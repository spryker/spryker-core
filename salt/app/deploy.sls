install app:
  pkg.installed:
    - pkgs:
      - doxygen
      - graphviz
      - libjpeg-progs

/etc/deploy:
  file.directory:
    - user: root
    - group: root
    - dir_mode: 755

#/etc/deploy/deploy.rb:
#/etc/deploy/functions.rb:
#/etc/deploy/config.rb:
#/etc/deploy/ssh_wrapper:
#/etc/deploy/deploy.key: