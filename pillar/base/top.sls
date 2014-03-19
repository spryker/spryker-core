prod:
  '*':
    - app
    - couchbase
    - user
    - postfix

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
