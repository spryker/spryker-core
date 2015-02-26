#
# Define service reload commands here, so that the state spryker does not depend
# on the other states.
#
# The commands here are defined as "cmd.wait", so they only get called if they are
# included in watch_in element and change is triggered.


reload-php-fpm:
  cmd.wait:
    - name: /etc/init.d/php5-fpm reload

reload-nginx:
  cmd.wait:
    - name: /etc/init.d/nginx reload
