prod:
  '*':
    - app
    - app.dwh
    - couchbase
    - user
    - elasticsearch
    - postfix
    - system

dev:
  '*':
    - app
    - app.dwh
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
