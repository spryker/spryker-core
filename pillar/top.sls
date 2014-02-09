base:
  '*':
    - user
    - app

  'deployment:prod':
    - match: grain
    - app.prod