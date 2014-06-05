base:
  pkg.installed:
    - pkgs:
      - git
      - unzip
      - pbzip2
      - zsh
      - screen
      - mc
      - curl
      - lsof
      - htop
      - iotop
      - dstat
      - telnet
      - make
      - python-apt
      {% if 'cronjobs' in grains.roles %}
        - mysql-client
      {% endif %}
