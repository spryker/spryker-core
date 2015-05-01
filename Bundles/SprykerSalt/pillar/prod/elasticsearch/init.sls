elasticsearch:
  clustername: pyz01
  expected_nodes: 1
  minimum_nodes: 1
  enable_multicast_discovery: true
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk

elasticsearch.environments:
  # Temporarly disabled mlockall until we'll start using elasticsearch - normally it should be true (that's default setting)
  mlockall: false
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk

kibana:
  hostname: kibana.project-yz.com
