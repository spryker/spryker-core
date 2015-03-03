#
# Display nice spryker message of the day
#
/etc/motd:
  file.managed:
    - source: salt://system/files/etc/motd

