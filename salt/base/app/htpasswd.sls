# Create and manage .htpasswd files
# Note - the paths here should be same as paths defined in grain app config

{% if 'web' in grains.roles %}
# The default password for production-zed (yves remains open)
/etc/nginx/htpasswd-zed:
  file.managed:
    - source: salt://app/files/nginx/htpasswd-zed
    - user: www-data
    - group: www-data
    - mode: 640

# The default password for staging (both yves and zed)
/etc/nginx/htpasswd-staging:
  file.managed:
    - source: salt://app/files/nginx/htpasswd-staging
    - user: www-data
    - group: www-data
    - mode: 640
{% endif %}
