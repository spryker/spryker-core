include:
{% if 'dwh_saiku' in grains.roles %}
  - .nginx_proxy 
{% endif %}
{% if 'dwh_pgsql' in grains.roles %}
  - .pgsql
{% endif %}