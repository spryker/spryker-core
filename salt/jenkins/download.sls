/data/deploy/download:
  file.directory:
    - mode: 700
    - makedirs: True

/data/deploy/download/jenkins-{{ pillar.jenkins.version }}.war:
  cmd.run:
    - cwd: /data/deploy/download
    - name: wget -q -O jenkins-{{ pillar.jenkins.version }}.war {{ pillar.jenkins.source }}
    - unless: test -f /data/deploy/download/jenkins-{{ pillar.jenkins.version }}.war
    - require:
      - file: /data/deploy/download