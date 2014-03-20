newrelic-sysmond:
  pkgrepo.managed:
    - humanname: Newrelic PPA
    - name: deb http://apt.newrelic.com/debian/ newrelic non-free
    - file: /etc/apt/sources.list.d/newrelic.list
    - keyid: 548C16BF
    - keyserver: pgp.mit.edu
    - require_in:
      - pkg: newrelic-sysmond
  pkg:
    - installed
  cmd.run:
    - name: nrsysmond-config --set license_key={{ pillar['newrelic']['license_key'] }}
    - unless: grep {{ pillar['newrelic']['license_key'] }} /etc/newrelic/nrsysmond.cfg
    - require_in:
        file: /etc/newrelic/nrsysmond.cfg
  file.managed:
    - name: /etc/newrelic/nrsysmond.cfg
    - replace: False
    - mode: 600
    - user: newrelic
    - group: newrelic
  service.running:
    - enable: True
    - watch:
      - pkg: newrelic-sysmond
      - file: /etc/newrelic/nrsysmond.cfg