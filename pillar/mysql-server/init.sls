mysql-server:
  environments:
    - testing
    - development
  users:
    - testing:
        password: mate20mg
        host: localhost

    - development:
        password: mate20mg
        host: localhost
