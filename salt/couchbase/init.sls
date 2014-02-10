#include:
#  - .cluster-setup

libssl:
  pkg:
    - installed
    - name: libssl0.9.8
    - require:
      - pkgrepo: pav-v2

couchbase-server:
  pkg:
    - installed
    - sources:
      - couchbase-server: http://packages.couchbase.com/releases/2.2.0/couchbase-server-enterprise_2.2.0_x86_64_openssl098.deb
    - require:
      - pkg: libssl
  service:
    - running
    - enable: True
    - require:
      - pkg: couchbase-server

couchbase-oneiric:
  pkgrepo.managed:
    - humanname: Couchbase - Ubuntu Oneiric repo 
    - name: deb http://packages.couchbase.com/ubuntu oneiric oneiric/main
    - file: /etc/apt/sources.list.d/project-a.list
    - key_url: http://packages.couchbase.com/ubuntu/couchbase.key

libcouchbase-dev:
  pkg.installed:
    - fromrepo: couchbase-oneiric

libcouchbase2-libevent:
  pkg.installed:
    - fromrepo: couchbase-oneiric