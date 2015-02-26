#
# Install package, remove default service
#

redis-server:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - require:
      - pkg: redis-server

# Remove default redis instance
/etc/redis/redis.conf:
  file.absent:
    - watch_in:
      - service: redis-server

# Init script for multiple instances
/etc/init.d/redis-server:
  file.managed:
    - source: salt://redis/files/etc/init.d/redis-server
    - watch_in:
      - service: redis-server
