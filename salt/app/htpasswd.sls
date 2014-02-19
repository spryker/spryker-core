# Create and manage .htpasswd files
# Note - the paths here should be aligned with paths defined in grain app config

{% if 'web' in grains.roles %}
# FIXME: there's a buggy implementation of that module in saltstack.
# It checks if apachectl command exists, and this command requires installing apache2 webserver
# It's actually enough to install apache2-utils to use htpasswd command.
production-zed:
  apache.useradd:
    - pwfile: /etc/nginx/htpasswd-zed
    - user: projecta
    - password: mate21mg

staging:
  apache.useradd:
    - pwfile: /etc/nginx/htpasswd-zed
    - user: projecta
    - password: mate21mg
{% endif %}