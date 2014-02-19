# Static files, included in NginX Vhosts
#
# Note: nginx vhosts and fpm-upstream's are created in environments.sls / stores.sls

/etc/nginx/yzed:
  file.recurse:
    - source: salt://app/files/nginx/yzed
