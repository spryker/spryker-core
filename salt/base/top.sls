#
# Topfile - used by salt ... state.highstate
#

base:
  # apply to all roles
  '*':
    - _debug
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
#    - elasticsearch.single

prod:
  # apply to all roles
  '*':
    - system
    - user
    - logstash
    - newrelic

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
    - newrelic.php

  # jenkins to run cronjob and indexers
  'roles:cronjobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins
    - newrelic.php

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
    - elasticsearch.single

  # elasticsearch
  'roles:elasticsearch_data':
    - match: grain
    - java
    - elasticsearch.environments


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
    - elasticsearch.single
    - elasticsearch.environments

  # dev tools
  'roles:dev':
    - match: grain
    - mysql-server
    - development
    - pound
    - mailcatcher
 