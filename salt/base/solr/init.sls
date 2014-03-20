# This state downloads and prepares to run solr.
# It does not deploy solr in any specific application server - this is done
# in tomcat.solr state

include:
  - .download
  - .logging
