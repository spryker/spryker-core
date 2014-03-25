postgresql-repo:
  pkgrepo.managed:
    - humanname: Postgresql repository (wheezy)
    - name: deb http://apt.postgresql.org/pub/repos/apt/ wheezy-pgdg main
    - file: /etc/apt/sources.list.d/postgresql.list
    - key_url: http://apt.postgresql.org/pub/repos/apt/ACCC4CF8.asc
    - require_in:
      - pkg: postgresql-9.3

postgresql-9.3:
  pkg.installed

data-dir:
  file.directory:
    - name: /data/pgsql
    - makedirs: true
    - user: postgres
    - group: postgres
    - require:
      - pkg: postgresql-9.3
  cmd.run:
    - name: rm -f /etc/postgresql/9.3/main/postgresql.conf && pg_createcluster --datadir /data/pgsql 9.3 main
    - unless: test -d /data/pgsql/base
    - cwd: /data/pgsql
    - require:
      - file: data-dir

/etc/postgresql/9.3/main/pg_hba.conf:
  file.managed:
    - source: salt://dwh/files/etc/postgresql/pg_hba.conf
    - template: jinja
    - require:
      - pkg: postgresql-9.3

/etc/postgresql/9.3/main/postgresql.conf:
  file.managed:
    - source: salt://dwh/files/etc/postgresql/postgresql.conf
    - template: jinja
    - require:
      - pkg: postgresql-9.3
      - cmd: data-dir

postgresql:
  service: 
   - running
   - enable: true
   - reload: true
   - watch:
     - file: /etc/postgresql/9.3/main/pg_hba.conf
     - file: /etc/postgresql/9.3/main/postgresql.conf
   - require:
     - file: /etc/postgresql/9.3/main/pg_hba.conf
     - file: /etc/postgresql/9.3/main/postgresql.conf
     - cmd: data-dir

root:
  postgres_user.present:
   - login: true
   - superuser: true
   - require:
     - service: postgresql

dwh:
  postgres_user.present:
   - login: true
   - require:
     - service: postgresql

  postgres_database.present:
   - owner: dwh
   - require:
     - service: postgresql

