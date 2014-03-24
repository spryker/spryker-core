# Elasticsearch - install and use single setup
#
# Don't use this state at all :) it's for backward compatibility only.
#
# You should specify elasticsearch.single or elasticsearch.environments only! 
# For multiple instances setup, don't include this state - use elasticsearch.environments only!

include:
  - .install
  - .single