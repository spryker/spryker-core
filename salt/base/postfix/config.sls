
/etc/postfix/main.cf:
  file.managed:
    - source: salt://postfix/files/main.cf
    - template: jinja
    - user: root
    - group: root
    - mode: 644
    - context:
        postfix: {{ pillar.postfix }}
    - require:
      - pkg: postfix

/etc/mailname:
  file.managed:
   - source: salt://postfix/files/etc_mailname
   - template: jinja
   - user: root
   - group: root
   - mode: 644
   - defaults:
       hostname: {{ grains['fqdn'] }}
   #- context:
   #    hostname: <TODO maybe add Fallback if fqdn not available>

/etc/postfix/sasl_passwd:
  file.managed:
   - source: salt://postfix/files/sasl_passwd
   - template: jinja
   - user: root
   - group: root
   - mode: 644
   - defaults:
       postfix: {{ pillar.postfix }}