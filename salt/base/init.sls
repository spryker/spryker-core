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

pav:
  pkgrepo.managed:
    - humanname: Project-A APT wheezy
    - name: deb http://apt2.test-a-team.com/wheezy ./
    - file: /etc/apt/sources.list.d/project-a-wheezy.list
    - key_url: http://apt.test-a-team.com/key.gpg



include:
  - .minion
  - .sudoers
  - .vim
  - .time
  - .firewall


# Preferences (editors, skel, .bashrc, etc)
# /data
# sysctl # Max number of sockets net.core.somaxconn = 4096
# firewall
