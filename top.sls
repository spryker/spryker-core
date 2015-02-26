#
# Topfile - used by salt ... state.highstate
#

# Production setup - we apply specific states to machines
prod:
  # apply to all roles
  '*':
    - hosting
    - system
#    - user
#    - newrelic
#
#  # php and application code
#  'roles:app':
#    - match: grain
#    - php
#    - spryker
#
#  # nginx and web components
#  'roles:web':
#    - match: grain
#    - nginx
#    - newrelic.php
#
#  # jenkins to run cronjob and indexers
#  'roles:cronjobs':
#    - match: grain
#    - spryker
#    - java
#    - jenkins
#    - newrelic.php
#

#  # elasticsearch (for spryker data)
#  'roles:elasticsearch_data':
#    - match: grain
#    - java
#    - elasticsearch
#
#  # ELK stack
#  'roles:elk_elasticsearch':
#    - match: grain
#    - java
#    - elk.elasticsearch
#  'roles:elk_logstash':
#    - match: grain
#    - java
#    - elk.logstash
#  'roles:elk_elasticsearch':
#    - match: grain
#    - elk.kibana

dev:
  # apply all states on a single machine, don't divide by roles
  '*':
    - system
    - hosting
    - user
    - postfix
    - mysql-server
    - postgresql
    - pound
    - ruby
    - nodejs
    - php
    - java
    - development
    - mailcatcher
    - nginx
    - spryker
    - jenkins
    - redis
    - elasticsearch
    - elk
