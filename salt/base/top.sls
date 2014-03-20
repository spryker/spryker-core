#
# Topfile - used by salt ... state.highstate
#

base:
  # apply to all roles
  '*':
    - system
    - user
    - logstash

  # couchbase
  'roles:couchbase':
    - match: grain
    - couchbase

  # php and application code
  'roles:app':
    - match: grain
    - php
    - app

  # nginx and web components
  'roles:web':
    - match: grain
    - nginx

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins

  # solr
  'roles:solr':
    - match: grain
    - java
    - tomcat
    - solr

  # activemq
  'roles:queue':
    - match: grain
    - java

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
    - java
#    - elasticsearch

prod:
  # apply to all roles
  '*':
    - system
    - user
    - logstash

  # couchbase
  'roles:couchbase':
    - match: grain
    - couchbase

  # php and application code
  'roles:app':
    - match: grain
    - php
    - app

  # nginx and web components
  'roles:web':
    - match: grain
    - nginx

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins

  # solr
  'roles:solr':
    - match: grain
    - java
    - tomcat
    - solr

  # activemq
  'roles:queue':
    - match: grain
    - java

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
    - java
  # - elasticsearch

  # newrelic for server monitoring - prod only
  'deployment:prod':
    - match: grain
    - newrelic

  # newrelic for app monitoring - prod only, web servers
  'G@deployment:prod and G@roles:web':
    - match: compound
    - newrelic.php

  # newrelic for app monitoring - prod only, job servers
  'G@deployment:prod and G@roles:cronjobs':
    - match: compound
    - newrelic.php

dev:
  # apply to all roles
  '*':
    - system
    - user
    - logstash

  # couchbase
  'roles:couchbase':
    - match: grain
    - couchbase

  # php and application code
  'roles:app':
    - match: grain
    - php
    - app

  # nginx and web components
  'roles:web':
    - match: grain
    - nginx

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins

  # solr
  'roles:solr':
    - match: grain
    - java
    - tomcat
    - solr

  # activemq
  'roles:queue':
    - match: grain
    - java

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
    - java
#    - elasticsearch

  # dev tools
  'roles:dev':
    - match: grain
    - mysql-server
    - elasticsearch
    - development
    - pound
