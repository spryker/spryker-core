# Static files, included in NginX Vhosts
#
# Note: nginx vhosts and fpm-upstream's are created in environments.sls / stores.sls

{% if 'web' in grains.roles %}
# We don't want apache at all
apache2:
  pkg:
    - remove

/etc/nginx/yzed:
  file.recurse:
    - source: salt://app/files/nginx/yzed
{% endif %}