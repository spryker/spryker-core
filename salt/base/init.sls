base:
  pkg.installed:
    - pkgs:
      - git
      - htop
      - zsh
      - curl
      - mc
      - unzip


  pkgrepo.managed:
    - humanname: Project-A APT wheezy
    - name: http://apt2.test-a-team.com/wheezy/
    - dist: .
    - file: /etc/apt/sources.list.d/project-a-wheezy.list


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