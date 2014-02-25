deploy:
  git_url: git@codebasehq.com:project-a/core/pyz.git

# Use apache archive to download older versions - mirros have only latest releases
solr:
  version: 4.6.1
  source: http://apache.mirrors.pair.com/lucene/solr/4.6.1/solr-4.6.1.tgz

stores:
  DE:
    locale: de_DE
    appdomain: '00'
