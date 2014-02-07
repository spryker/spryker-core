base:
  pkg.installed:
    - pkgs:
      - git
      - htop
      - zsh
      - curl
      - mc
      - unzip
deb http://apt.test-a-team.com/ squeeze main non-free
deb http://apt2.test-a-team.com/wheezy/ ./


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