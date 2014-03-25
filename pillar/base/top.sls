prod:
  '*':
    - app
    - dwh
    - couchbase
    - user
    - elasticsearch
    - postfix
    - system

dev:
  '*':
    - app
    - dwh
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
