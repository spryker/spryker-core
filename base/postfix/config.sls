#
# Configuratiuon files for local postfix server
#

# Main configuration file
/etc/postfix/main.cf:
  file.managed:
    - source: salt://postfix/files/etc/postfix/main.cf
    - template: jinja
    - user: root
    - group: root
    - mode: 644
    - context:
        postfix: {{ pillar.postfix }}
    - require:
      - pkg: postfix
    - watch_in:
      - service: postfix

# Hostname for outgoing mails
/etc/mailname:
  file.managed:
   - source: salt://postfix/files/etc/mailname
   - template: jinja
   - user: root
   - group: root
   - mode: 644

# SASL authentication for using third-party relays with authentication
/etc/postfix/sasl_passwd:
  file.managed:
   - source: salt://postfix/files/etc/postfix/sasl_passwd
   - template: jinja
   - user: root
   - group: root
   - mode: 644
   - defaults:
       postfix: {{ pillar.postfix }}

run-postmap:
  cmd.wait:
    - name: /usr/sbin/postmap /etc/postfix/sasl_passwd
    - cwd: /
    - watch:
      - file: /etc/postfix/sasl_passwd
    - require:
      - file: /etc/postfix/sasl_passwd
