mysql-server:
  databases:
    - US_testing_zed
    - US_production_zed
  users:
    - user: testing
      password: mate20mg
      host: localhost
      permissions:
#        - grant: select,insert,update
#          database: pengyao.*
        - grant: all
          database: US_testing_zed.*

    - user: production
      password: mate20mg
      host: localhost
      permissions:
        - grant: all
          database: US_production_zed.*
