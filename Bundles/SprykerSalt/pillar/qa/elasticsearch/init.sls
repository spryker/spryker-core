elasticsearch:
  clustername: pyz01
  expected_nodes: 1
  minimum_nodes: 1
  enable_multicast_discovery: false
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk
