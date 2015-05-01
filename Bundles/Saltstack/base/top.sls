#
# Topfile - used by salt ... state.highstate
#

# Production setup - we apply specific states to machines
prod:
  # apply to all roles
  '*':
    - system
    - hosting
    - user
    - postfix
    - newrelic

  # php and application code
  'roles:app':
    - match: grain
    - php
    - spryker
    - elk.logstash

  # nginx and web components
  'roles:web':
    - match: grain
    - nginx
    - newrelic.php

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
    - spryker
    - java
    - jenkins
    - newrelic.php
    - elk.logstash

  # elasticsearch (for spryker data)
  'roles:elasticsearch':
    - match: grain
    - java
    - elasticsearch

  # Rabbit MQ
  'roles:queue':
    - match: grain
    - rabbitmq

  # Redis
  'roles:redis':
    - match: grain
    - redis

  # Database
  'roles:postgresq':
    - match: grain
    - postgresql
  'roles:mysql':
    - match: grain
    - mysql-server

  # ELK stack
  'roles:elk_elasticsearch':
    - match: grain
    - java
    - elk.elasticsearch
  'roles:elk_kibana':
    - match: grain
    - elk.kibana

dev:
  # apply all states on a single machine, don't divide by roles
  '*':
    - system
    - hosting
    - user
    - postfix
    - mysql-server
    - postgresql
    - rabbitmq
    - ruby
    - nodejs
    - php
    - java
    - development
    - mailcatcher
    - nginx
    - pound
    - jenkins
    - redis
    - elasticsearch
    - elk
    - spryker
