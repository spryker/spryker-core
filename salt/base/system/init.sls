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


include:
  - .repositories
  - .filesystems
  - .minion
  - .sudoers
  - .vim
  - .time
  - .firewall
  - .sysctl
