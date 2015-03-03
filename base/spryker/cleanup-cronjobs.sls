#
# Cronjobs for cleanups of temporary / unused files
#

/etc/cron.d:
  file.recurse:
    - source: salt://spryker/files/etc/cron.d
