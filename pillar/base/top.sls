prod:
  '*':
    - app
    - couchbase
    - user
    - postfix
    - system

dev:
  '*':
    - app
    - couchbase
    - user
    - mysql-server
    - elasticsearch

qa:
  '*':
    - app
    - couchbase
    - user
    - mysql-server
    - elasticsearch
