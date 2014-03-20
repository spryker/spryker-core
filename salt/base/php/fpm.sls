php5-fpm:
  service:
{% if 'web' in grains.roles %}
    - running
    - enable: True
{% else %}
    - dead
    - enable: False
{% endif %}
