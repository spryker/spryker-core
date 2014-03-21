prod:
  '*':
    - app
    - couchbase
    - user
    - elasticsearch
    - postfix
    - system

dev:
  '*':
    - app
    - couchbase
    - user
    - elasticsearch
    - mysql-server

qa:
  '*':
    - app
    - couchbase
    - user
    - elasticsearch
    - mysql-server
