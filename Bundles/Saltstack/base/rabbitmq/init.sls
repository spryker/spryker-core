#
# Install RabbitMQ (Message Queue)
#

include:
  - .setup
# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:rabbitmq', False) %}
  - .update
{% endif %}
