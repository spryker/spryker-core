prod:
  '*':
    - app
    - dwh
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
    - user
    - elasticsearch
    - mysql-server
