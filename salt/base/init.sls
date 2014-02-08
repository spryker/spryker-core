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

vim:
  pkg:
    - installed
  alternatives.set:
    - name: editor
    - path: /usr/bin/vim.basic
    - require:
      - pkg: vim

include:
  - .sudoers