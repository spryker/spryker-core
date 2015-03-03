#
# Elasticsearch - install
#
# This state performs elasticsearch installation and prepares instances for
# spryker environments.
#


include:
  - .install
  - .environments
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:elasticsearch', False) %}
  - .update
{% endif %}
