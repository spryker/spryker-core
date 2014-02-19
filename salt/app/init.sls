include:
  - .install
  - .nodejs
  - .ruby
  - .deploy
  - .environments
  - .stores


/tmp/test:
  file.managed:
    - source: salt://app/files/test
    - template: jinja
