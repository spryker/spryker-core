elasticsearch.default:
  clustername: pyz01
  expected_nodes: 2
  minimum_nodes: 3
  enable_multicast_discovery: true
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk

elasticsearch.production:
  clustername: pyz-production01
  expected_nodes: 3
  minimum_nodes: 2
  enable_multicast_discovery: true
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk

elasticsearch.staging:
  clustername: pyz-production01
  expected_nodes: 3
  minimum_nodes: 2
  enable_multicast_discovery: true
  heap_size: 384m
  plugins:
    head:
      name: mobz/elasticsearch-head
    bigdesk:
      name: lukas-vlcek/bigdesk

