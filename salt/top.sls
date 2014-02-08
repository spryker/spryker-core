#
# Topfile - used by salt ... state.highstate
#

base:
  # apply to all roles
  '*':
    - base
    - user

  # couchbase
  'roles:couchbase':
    - match: grain
    - couchbase

  # php and application code
  'roles:app':
    - match: grain

  # nginx and web components
  'roles:web':
    - match: grain

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain

  # solr master
  'roles:solr_master':
    - match: grain

  # solr slave
  'roles:solr':
    - match: grain

  # activemq
  'roles:queue':
    - match: grain

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
