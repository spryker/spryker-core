#
# Topfile - used by salt ... state.highstate
#

base:
  # apply to all roles
  '*':
    - base
    - user
#    - logstash
#    - newrelic

  # couchbase
  'roles:couchbase':
    - match: grain
    - couchbase

  # php and application code
  'roles:app':
    - match: grain
#    - php
    - app

  # nginx and web components
  'roles:web':
    - match: grain
    - nginx
#    - php-fpm

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
#    - tomcat

  # solr master
  'roles:solr_master':
    - match: grain
#    - tomcat

  # solr slave
  'roles:solr':
    - match: grain
#    - tomcat

  # activemq
  'roles:queue':
    - match: grain
#    - tomcat

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
#    - elasticsearch
