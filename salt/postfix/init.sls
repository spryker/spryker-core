postfix:
  pkg:
    - installed
  service.running:
    - require:
      - pkg: postfix
    - watch:
      - file: /etc/postfix/main.cf

procmail:
  pkg.installed

include:
  - .config
  - .postmap