deploy:
  git_url: git@codebasehq.com:project-a/core/pyz.git

# Use apache archive to download older versions - mirros have only latest releases
solr:
  version: 4.6.1
  source: http://archive.apache.org/dist/lucene/solr/4.6.1/solr-4.6.1.tgz

jenkins:
  version: 1.532.2
  source: http://mirrors.jenkins-ci.org/war-stable/1.532.2/jenkins.war

stores:
  DE:
    locale: de_DE
    appdomain: '00'
