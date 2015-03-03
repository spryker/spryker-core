#
# Install RabbitMQ (message queue broker)
#

rabbitmq-server:
  pkg.installed:
    - name: rabbitmq-server
  service.running:
    - enable: true
    - require:
      - pkg: rabbitmq-server
