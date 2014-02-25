include:
  - .install
  - .instances
{{ if 'solr' in grains.roles }}
  - .solr
{{ endif }}