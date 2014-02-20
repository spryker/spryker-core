base:
  '*':
    - user
    - app
    - couchbase

  'deployment:prod':
    - match: grain
    - app.prod
    - couchbase.prod

  'deployment:dev':
    - match: grain
    - app.dev

  'roles:dev':
    - match: grain
    - mysql-server
    - elasticsearch
    - app.dev

