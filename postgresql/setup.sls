#
# Install and configure PostgreSQL database
#
# This state manages the configuration of PostgreSQL database, creates
# data directory in /data and sets up default cluster (main).
# Note that this configuration does not include any failover and/or replication.
# It is suitable to run on development and QA environments.
#
# To deploy Spryker in production, a stable and secure PostgreSQL setup is
# recommended, which includes:
#  - backup
#  - replication
#  - hot-standby slave
#  - failover mechanism
#  - appropiate hardware

postgresql:
  pkg.installed:
    - name: postgresql-9.4
  service.running:
   - enable: true
   - reload: true
   - watch:
     - file: /etc/postgresql/9.4/main/pg_hba.conf
     - file: /etc/postgresql/9.4/main/postgresql.conf
   - require:
     - file: /etc/postgresql/9.4/main/pg_hba.conf
     - file: /etc/postgresql/9.4/main/postgresql.conf
     - cmd: data-dir

data-dir:
  file.directory:
    - name: /data/pgsql
    - makedirs: true
    - user: postgres
    - group: postgres
    - require:
      - pkg: postgresql
  cmd.run:
    - name: /etc/init.d/postgresql stop && rm -f /etc/postgresql/9.4/main/* && pg_createcluster --datadir /data/pgsql 9.4 main
    - unless: test -d /data/pgsql/base
    - cwd: /data/pgsql
    - require:
      - file: data-dir

/etc/postgresql/9.4/main/pg_hba.conf:
  file.managed:
    - source: salt://postgresql/files/etc/postgresql/pg_hba.conf
    - template: jinja
    - require:
      - pkg: postgresql
      - cmd: data-dir
    - watch_in:
      - service: postgresql

/etc/postgresql/9.4/main/postgresql.conf:
  file.managed:
    - source: salt://postgresql/files/etc/postgresql/postgresql.conf
    - template: jinja
    - require:
      - pkg: postgresql
      - cmd: data-dir
    - watch_in:
      - service: postgresql

root:
  postgres_user.present:
   - login: true
   - superuser: true
   - require:
     - service: postgresql

# Include autoupdate if configured to do so
{% if salt['pillar.get']('autoupdate:postgresql', False) %}
include:
  - .update
{% endif %}
