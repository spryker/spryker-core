#
# Install and configure PostgreSQL database
#
# This state manages the configuration of PostgreSQL database, creates
# data directory in /data and sets up default cluster (main).
# Note that this configuration does not include any failover and/or replication.
# It is suitable to run on development and QA environments.
#
# To deploy Spryker in production, a stable and secure PostgreSQL setup is
# recommended, which includes:
#  - backup
#  - replication
#  - hot-standby slave
#  - failover mechanism
#  - appropiate hardware

include:
  - .setup
  - .credentials
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:postgresql', False) %}
  - .update
{% endif %}
