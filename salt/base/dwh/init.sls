include:
{% if 'dwh_saiku' in grains.roles %}
  - .nginx_proxy 
{% endif %}