#
# Populate NginX configuration includes, used in VHost definitions.
#

{% if 'web' in grains.roles %}
/etc/nginx/spryker:
  file.recurse:
    - source: salt://spryker/files/etc/nginx/spryker
    - watch_in:
      - cmd: reload-nginx
{% endif %}
