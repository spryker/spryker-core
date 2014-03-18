
run-postmap:
  cmd.wait:
    - name: /usr/sbin/postmap /etc/postfix/sasl_passwd
    - cwd: /
    - watch:
      - file: /etc/postfix/sasl_passwd
    - require:
      - file: /etc/postfix/sasl_passwd