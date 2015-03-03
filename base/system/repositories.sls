#
# Setup additional debian package repositories
#

# Required for https-based repositories
apt-transport-https:
  pkg.installed

# Base debian repositories
apt-get-update:
  cmd.wait:
    - name: apt-get update
/etc/apt/sources.list:
  file.managed:
    - source: salt://system/files/etc/apt/sources.list
    - template: jinja
    - watch_in:
       - cmd: apt-get-update

# Additional software repositories
dotdeb:
  pkgrepo.managed:
    - humanname: DotDeb repo (wheezy)
    - name: deb http://packages.dotdeb.org wheezy all
    - file: /etc/apt/sources.list.d/dotdeb.list
    - key_url: http://www.dotdeb.org/dotdeb.gpg

dotdeb-php56:
  pkgrepo.managed:
    - humanname: DotDeb PHP-5.6 repo (wheezy)
    - name: deb http://packages.dotdeb.org wheezy-php56 all
    - file: /etc/apt/sources.list.d/dotdeb-php56.list
    - key_url: http://www.dotdeb.org/dotdeb.gpg

elasticsearch-repo:
  pkgrepo.managed:
    - humanname: Official Elasticsearch Repository
    - name: deb http://packages.elasticsearch.org/elasticsearch/{{ salt['pillar.get']('elasticsearch:version', '1.4') }}/debian stable main
    - file: /etc/apt/sources.list.d/elasticsearch.list
    - key_url: http://packages.elasticsearch.org/GPG-KEY-elasticsearch

logstash-repo:
  pkgrepo.managed:
    - humanname: Official Logstash Repository
    - name: deb http://packages.elasticsearch.org/logstash/{{ salt['pillar.get']('logstash:version', '1.4') }}/debian stable main
    - file: /etc/apt/sources.list.d/logstash.list
    - key_url: http://packages.elasticsearch.org/GPG-KEY-elasticsearch


wheezy-backports-repo:
  pkgrepo.managed:
    - humanname: Debian Wheezy Backports repository
    - name: deb http://ftp.uk.debian.org/debian wheezy-backports main
    - file: /etc/apt/sources.list.d/backports.list

nodesource-node-repo:
  pkgrepo.managed:
    - humanname: NodeSource NodeJS repository
    - name: deb https://deb.nodesource.com/node012 wheezy main
    - file: /etc/apt/sources.list.d/nodesource-node.list
    - key_url: https://deb.nodesource.com/gpgkey/nodesource.gpg.key

jenkins-repo:
  pkgrepo.managed:
    - humanname: Jenkins repository
    - name: deb http://pkg.jenkins-ci.org/debian binary/
    - file: /etc/apt/sources.list.d/jenkins.list
    - key_url: http://pkg.jenkins-ci.org/debian/jenkins-ci.org.key

postgresql-repo:
  pkgrepo.managed:
    - humanname: Postgresql repository (wheezy)
    - name: deb http://apt.postgresql.org/pub/repos/apt/ wheezy-pgdg main
    - file: /etc/apt/sources.list.d/postgresql.list
    - key_url: http://apt.postgresql.org/pub/repos/apt/ACCC4CF8.asc

rabbitmq-repo:
  pkgrepo.managed:
    - humanname: RabbitMQ repository
    - name: deb http://www.rabbitmq.com/debian/ testing main
    - file: /etc/apt/sources.list.d/rabbitmq.list
    - key_url: https://www.rabbitmq.com/rabbitmq-signing-key-public.asc
