include:
  - .install
  - .instances

{% if 'cronjobs' in grains.roles or 'dwh_jobs' in grains.roles %}
  - .jenkins
{% endif %}
