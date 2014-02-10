# Install couchbase libraries
# We use here couchbase repository for ubuntu oneiric, which works fine with debian wheezy

couchbase-oneiric:
  pkgrepo.managed:
    - humanname: Couchbase - Ubuntu Oneiric repo 
    - name: deb http://packages.couchbase.com/ubuntu oneiric oneiric/main
    - file: /etc/apt/sources.list.d/project-a.list
    - key_url: http://packages.couchbase.com/ubuntu/couchbase.key

libcouchbase-dev:
  pkg.installed:
    - fromrepo: couchbase-oneiric
    - require:
      - couchbase-oneiric.pkgrepo

libcouchbase2-libevent:
  pkg.installed:
    - fromrepo: couchbase-oneiric
    - require:
      - couchbase-oneiric.pkgrepo
