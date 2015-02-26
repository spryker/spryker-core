#
# Setup time-related parameters
#

# Set system timezone - we always run operating system in ETC
# regardless of location and application settings
Etc/UTC:
  timezone.system:
    - utc: True

# NTP for time synchronization
ntp:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - require:
      - pkg: ntp
