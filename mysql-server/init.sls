#
# Prepare local development MySQL server
#

include:
  - .setup
  - .credentials
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:mysql', False) %}
  - .update
{% endif %}
