# Cronjobs for cleanups

/etc/cron.d:
  file.recurse:
    - source: salt://app/files/cron.d
