base:
  pkg.installed:
    - pkgs:
      - git
      - unzip
      - pbzip2
      - zsh
      - screen
      - mc
      - curl
      - lsof
      - htop
      - iotop
      - dstat
      - telnet
      - make
      - python-apt

pav-v1:
  pkgrepo.managed:
    - humanname: Project-A APT repo (wheezy/squeeze)
    - name: deb http://apt.test-a-team.com squeeze main
    - file: /etc/apt/sources.list.d/project-a.list
    - key_url: http://apt.test-a-team.com/key.gpg

pav-v2:
  pkgrepo.managed:
    - humanname: Project-A APT repo (wheezy)
    - name: deb http://apt2.test-a-team.com/wheezy ./
    - file: /etc/apt/sources.list.d/project-a-wheezy.list
    - key_url: http://apt.test-a-team.com/key.gpg

dotdeb:
  pkgrepo.managed:
    - humanname: DotDeb repo (wheezy)
    - name: deb http://packages.dotdeb.org wheezy all
    - file: /etc/apt/sources.list.d/dotdeb.list
    - key_url: http://www.dotdeb.org/dotdeb.gpg

logstash-repo:
  pkgrepo.managed:
    - humanname: Official Logstash Repository @Elasticsearch
    - name:  deb http://packages.elasticsearch.org/logstash/1.3/debian stable main
    - file: /etc/apt/sources.list.d/elasticsearch.list
    - key_url: http://packages.elasticsearch.org/GPG-KEY-elasticsearch

elasticsearch-repo:
  pkgrepo.managed:
    - humanname: Official Elasticsearch Repository
    - name:  deb http://packages.elasticsearch.org/elasticsearch/1.0/debian stable main
    - file: /etc/apt/sources.list.d/elasticsearch.list
    - key_url: http://packages.elasticsearch.org/GPG-KEY-elasticsearch

wheezy-backports-repo:
  pkgrepo.managed:
    - humanname: Debian Wheezy Backports repository
    - name:  deb http://uk.debian.org/debian wheezy-backports main
    - file: /etc/apt/sources.list.d/backports.list
  

include:
  - .filesystems
  - .minion
  - .sudoers
  - .vim
  - .time
  - .firewall
  - .sysctl

# Preferences (editors, skel, .bashrc, etc)
# sysctl # Max number of sockets net.core.somaxconn = 4096
