base:
  '*':
    - app
    - couchbase
    - postfix

  'deployment:prod':
    - match: grain
    - app.prod
    - couchbase.prod
    - user.prod

  'deployment:dev':
    - match: grain
    - app.dev
    - user.dev

  'roles:dev':
    - match: grain
    - mysql-server
    - elasticsearch
    - app.dev
    - user.dev
