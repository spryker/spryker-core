# This pillar of salt is used to generate 'global variables' based on values provided in app pillar
# We follow the DRY rule - each information is entered only once
#
# Example - database names - sample name is DE_production_zed
# This actually can be generated as { store }_{ environment }_zed
{% set c="yes" %}
{% set env_ports = { 'production': 5, 'staging': 3, 'testing': 1, 'development': 0 } %}


gen:
  a: yes
  b: {{ "yes" }}
  c: "{{ c }}"
  d: {{ env_ports }}
  e: "{{ pillar }}"

environments:
  t: {{ env_ports }}
  a: m
# { % - for environment, environment_details in pillar.environments.items() %}
  tomcat:
    generated_port: "{{ env_ports }}"
# { % - for store, store_details in pillar.stores.items() %}
# { % - endfor %}
# { % - endfor %}