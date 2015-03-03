#
# Install mailcatcher - http://mailcatcher.me/
#
# MailCatcher runs a super simple SMTP server which catches any message sent to it to display in a web interface.
# Mails delivered via smtp to 127.0.0.1:1025 will be visible in web browser on http://127.0.0.1:1080

libsqlite3-dev:
  pkg.installed:
    - require_in:
      - pkg: mailcatcher

mailcatcher-init-script:
  file.managed:
    - name: /etc/init.d/mailcatcher
    - mode: 0755
    - source: salt://mailcatcher/files/etc/init.d/mailcatcher

mailcatcher:
  gem:
    - installed
  service:
    - running
    - enable: True
    - require:
      - file: mailcatcher-init-script
      - gem: mailcatcher
