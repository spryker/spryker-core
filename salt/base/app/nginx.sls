# Static configuration files, included in NginX Vhosts
#
# Note: nginx vhosts and fpm-upstream's are created in environments.sls / stores.sls

{% if 'web' in grains.roles %}
/etc/nginx/yzed:
  file.recurse:
    - source: salt://app/files/nginx/yzed
  require:
    - package: nginx
  watch_in:
    - service: nginx
{% endif %}
