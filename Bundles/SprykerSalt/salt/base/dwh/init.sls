include:
{% if 'dwh_saiku' in grains.roles %}
  - .nginx_proxy
  - .cubes
{% endif %}
{% if 'dwh_database' in grains.roles %}
  - .pgsql
{% endif %}