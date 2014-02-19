# Static files, included in NginX Vhosts
#
# Note: nginx vhosts and fpm-upstream's are created in environments.sls / stores.sls

{% if 'web' in grains.roles %}
# We install apache2 only for apache2-utils to workaround buggy saltstack apache module
apache2:
  pkg:
    - installed
  service:
    - dead
    - enable: False
    - require:
      - pkg: apache

/etc/nginx/yzed:
  file.recurse:
    - source: salt://app/files/nginx/yzed
{% endif %}