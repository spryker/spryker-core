redis-server:
  pkg.installed:
    - name: redis-server
  service:
    - running

redis-tools:
  pkg.installed:
    - name: redis-tools
  service:
    - running

