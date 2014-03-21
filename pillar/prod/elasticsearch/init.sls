elasticsearch:
  clustername: pyz01
  expected_nodes: 3
  minimum_nodes: 2
  enable_multicast_discovery: true
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk
