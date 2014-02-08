install app:
  pkg.installed:
    - pkgs:
      - doxygen
      - graphviz
      - libjpeg-progs

/data/shop:
  file.directory:
    - makedirs: true
    - user: www-data
    - group: www-data
    - dir_mode: 755

/data/logs:
  file.directory:
    - makedirs: true
    - user: www-data
    - group: www-data
    - dir_mode: 755

/data/logs:
  file.directory:
    - makedirs: true
    - user: www-data
    - group: www-data
    - dir_mode: 755

include:
  - .nodejs
  - .ruby
