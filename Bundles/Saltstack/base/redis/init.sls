#
# This state downloads and prepares to run Redis-server
#
# Note that this state should be used only in non-production environments,
# as we do not setup any replication/failover mechanism via salt.
# Production environments should run either master-slave replication with failover,
# redis cluster or managed redis (e.g. ObjectRocket at Rackspace or ElastiCache at AWS)

include:
  - .install
  - .environments
