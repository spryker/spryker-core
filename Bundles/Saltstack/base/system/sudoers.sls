#
# Setup sudo configuration file
#

sudo:
  pkg.installed

/etc/sudoers:
  file.managed:
    - source: salt://system/files/etc/sudoers
    - template: jinja
