#
# Update rabbitmq-server package
#
# Note: this state is included only if pillar setting autoupdate:rabbitmq is true

update-rabbitmq:
  pkg.latest:
    - name: rabbitmq-server
