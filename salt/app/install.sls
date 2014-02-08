install:
  pkg.installed:
    - pkgs:
      - doxygen
      - graphviz
      - libjpeg-progs

include:
  - .nodejs
  - .ruby
