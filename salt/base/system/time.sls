# Set system timezone
Etc/UTC:
  timezone.system:
    - utc: True

# NTP for time sync
ntp:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - require:
      - pkg: ntp
