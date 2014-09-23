redis-server:
  pkg:
    - installed
  service.running:
    - require:
      - pkg: redis-server

redis-tools:
  pkg:
    - installed
