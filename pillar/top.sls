base:
  '*':
    - user
    - app

  'deployment:prod':
    - match: grain
    - app.prod

  'deployment:dev':
    - match: grain
    - app.dev

  'roles:dev':
    - match: grain
    - mysql-server
    - elasticsearch
    - app.dev
