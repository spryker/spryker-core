#
# Install NodeJS and npm modules in global mode
#

nodejs:
  pkg.installed

gulp:
  npm.installed:
    - require:
      - pkg: nodejs

grunt-cli:
  npm.installed:
    - require:
      - pkg: nodejs


# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:nodejs', False) %}
include:
  - .update
{% endif %}
