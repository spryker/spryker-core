#
# Create initial .htpasswd files
# Note - the paths here should be same as paths defined in pillar app config
# Files have replace: False, which means that the contents of the files will
# not be forced if the files will be changed manually on the servers. This
# state will create the files only if they don't exist (setup initial password).
#

{% if 'web' in grains.roles %}
# The default password for production-zed (yves remains open)
/etc/nginx/htpasswd-zed:
  file.managed:
    - source: salt://spryker/files/etc/nginx/htpasswd-zed
    - user: www-data
    - group: www-data
    - mode: 640
    - replace: False

# The default password for staging (both yves and zed)
/etc/nginx/htpasswd-staging:
  file.managed:
    - source: salt://spryker/files/etc/nginx/htpasswd-staging
    - user: www-data
    - group: www-data
    - mode: 640
    - replace: False
{% endif %}
