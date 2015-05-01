#
# ELK
#
# This state prepares the Elasticsearch-Logstash-Kibana stack for processing logs
# Elasticsearch for logs should not be confused with elasticsearch instances
# usef for Spryker store. Those are two completely seperated elasticsearch installations

include:
  - .elasticsearch
  - .logstash
  - .kibana
