#
# Topfile - used by salt ... state.highstate
#

base:
  # apply to all roles
  '*':
    - _debug
    - system
    - user

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
    - newrelic

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
#  'roles:solr':
#    - match: grain
#    - java
#    - tomcat
#    - solr

  # activemq
#  'roles:queue':
#    - match: grain
#    - java

  # elasticsearch (for logs)
#  'roles:elasticsearch':
#    - match: grain
#    - java
#    - elasticsearch.single

  # elasticsearch (for yves&zed)
  'roles:elasticsearch_data':
    - match: grain
    - java
    - elasticsearch.environments

  # DWH - Saiku
  'roles:dwh_saiku':
    - match: grain
    - nginx
    - java
    - tomcat
    - dwh

  # DWH - ETL Jobs
  'roles:dwh_jobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins

  # DWH - PostgreSQL database
  'roles:dwh_database':
    - match: grain
    - dwh

dev:
  # apply to all roles
  '*':
    - system
    - user

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

#  # solr
#  'roles:solr':
#    - match: grain
#    - java
#    - tomcat
#    - solr

#  # activemq
#  'roles:queue':
#    - match: grain
#    - java

  # elasticsearch
  'roles:elasticsearch':
    - match: grain
    - java
    - elasticsearch.single
    - elasticsearch.environments

  # DWH - Saiku
  'roles:dwh_saiku':
    - match: grain
    - nginx
    - java
    - tomcat
    - dwh

  # DWH - ETL Jobs
  'roles:dwh_jobs':
    - match: grain
    - app
    - java
    - tomcat
    - jenkins

  # DWH - PostgreSQL database
  'roles:dwh_database':
    - match: grain
    - dwh

  # dev tools
  'roles:dev':
    - match: grain
    - mysql-server
    - development
    - pound
    - postfix
    - mailcatcher
