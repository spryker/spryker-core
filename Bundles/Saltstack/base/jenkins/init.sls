#
# This state downloads and prepares to run jenkins.
#

include:
  - .install
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:jenkins', False) %}
  - .update
{% endif %}
