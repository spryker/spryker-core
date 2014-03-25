include:
  - .install
  - .instances
{% if 'solr' in grains.roles %}
  - .solr
{% endif %}
{% if 'cronjobs' in grains.roles or 'dwh_jobs' in grains.roles %}
  - .jenkins
{% endif %}