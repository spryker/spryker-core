# Static files, included in NginX Vhosts
#
# Note: nginx vhosts and fpm-upstream's are created in environments.sls / stores.sls

{% if 'web' in grains.roles %}
/etc/nginx/yzed:
  file.recurse:
    - source: salt://base/app/files/nginx/yzed
{% endif %}
