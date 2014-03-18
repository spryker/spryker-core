vim:
  pkg:
    - installed
  alternatives.set:
    - name: editor
    - path: /usr/bin/vim.basic
    - require:
      - pkg: vim

/etc/vim/vimrc.local:
  file.managed:
    - source: salt://files/vimrc
    - require:
      - pkg: vim
