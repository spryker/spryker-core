#
# Setup PHP environment
#

include:
  - .dependencies
  - .install
  - .config
  - .extensions
  - .fpm
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:php', False) %}
  - .update
{% endif %}
