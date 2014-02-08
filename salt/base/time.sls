# Set system timezone
Etc/UTC:
  timezone.system:
    - utc: True

Update timezone:
  cmd.wait:
    - name: /usr/sbin/dpkg-reconfigure tzdata
    - cwd: /
    - watch:
      - timezone

#  cmd.run
#    - require:
#      - timezone.system: ntp


# NTP for time sync
ntp:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - require:
      - pkg: ntp
