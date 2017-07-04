language: php

php:
  - 5.6
  - 7.0

cache:
  directories:
    - vendor
    - $HOME/.composer/cache

env:
  global:
    - APPLICATION_ENV=development
    - APPLICATION_STORE=DE

install:
  - composer self-update && composer --version
  - composer install --no-interaction --prefer-dist

script:
  - vendor/bin/phpcs src --standard=vendor/spryker/code-sniffer/Spryker/ruleset.xml
  - vendor/bin/codecept run --env isolated --coverage-xml

after_success:
  - vendor/bin/coveralls -vvv

notifications:
  email: false
