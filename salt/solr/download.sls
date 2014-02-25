/data/deploy/download/solr:
  file.directory:
    - mode: 700
    - makedirs: True

solr.tgz:
  file.managed:
    - name: /data/deploy/download/solr/solr-{{ pillar.solr.version }}.tar.gz
    - source: {{ pillar.solr.source }}
    - require:
      - file: /data/deploy/download/solr

unpack-solr.tgz:
  cmd.run:
    - cwd: /data/deploy/download/solr
    - require:
      - file: solr.tgz
    - name: tar zxf solr-{{ pillar.solr.version }}.tgz
    - creates: /data/deploy/download/solr/solr-{{ pillar.solr.version }}/dist/solr-{{ pillar.solr.version }}.war

/data/deploy/download/solr-{{ pillar.solr.version }}.war:
  file.copy:
    - source: /data/deploy/download/solr/solr-{{ pillar.solr.version }}/dist/solr-{{ pillar.solr.version }}.war
    - require:
      - cmd: unpack-solr.tgz

