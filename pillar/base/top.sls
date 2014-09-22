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
    - user
    - elasticsearch
    - mysql-server
    - postfix

qa:
  '*':
    - app
    - couchbase
    - user
    - elasticsearch
    - mysql-server
