# This state downloads and prepares to run jenkins.
# It does not deploy solr in any specific application server - this is done
# in tomcat.jenkins state

include:
  - .download
