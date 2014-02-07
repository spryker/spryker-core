base:
  pkg.installed:
    - pkgs:
      - git
      - htop
      - zsh
      - curl
      - mc
      - unzip

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