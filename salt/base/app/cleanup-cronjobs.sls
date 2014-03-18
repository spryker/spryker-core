# Cronjobs for cleanups

/etc/cron.d:
  file.recurse:
    - source: salt://base/app/files/cron.d
