deploy:
  git_url: git@codebasehq.com:project-a/core/pyz.git
  git_hostname: codebasehq.com

# Use apache archive to download older versions - mirros have only latest releases
{% if 'dev' in grains.roles %}
solr:
  version: 4.6.1
  source: "http://vagrant:mate20mg@salt.project-yz.com/solr/4.6.1/solr-4.6.1.tgz"
{% else %}
solr:
  version: 4.6.1
  source: http://archive.apache.org/dist/lucene/solr/4.6.1/solr-4.6.1.tgz
{% endif %}

{% if 'dev' in grains.roles %}
jenkins:
  version: 1.532.2
  source: "http://vagrant:mate20mg@salt.project-yz.com/jenkins/war-stable/1.532.2/jenkins.war"
{% else %}
jenkins:
  version: 1.532.2
  source: http://mirrors.jenkins-ci.org/war-stable/1.532.2/jenkins.war
{% endif %}

stores:
  DE:
    locale: de_DE
    appdomain: '00'
