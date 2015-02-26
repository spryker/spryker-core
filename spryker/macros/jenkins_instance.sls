#
# Macro: Setup one jenkins instance per each environment
#

{% macro jenkins_instance(environment, environment_details, settings) -%}
# Jenkins data directory
/data/shop/{{ environment }}/shared/data/common/jenkins:
  file.directory:
    - mode: 755
    - user: www-data
    - group: www-data
    - makedirs: True

# Jenkins default configuration
/data/shop/{{ environment }}/shared/data/common/jenkins/config.xml:
  file.managed:
    - mode: 644
    - user: www-data
    - group: www-data
    - source: salt://spryker/files/jenkins_instance/config.xml
    - replace: False
    - template: jinja
    - context:
      environment: {{ environment }}
    - require:
      - file: /data/shop/{{ environment }}/shared/data/common/jenkins
    - watch_in:
      - service: jenkins-{{ environment }}

# Service init script
/etc/init.d/jenkins-{{ environment }}:
  file.managed:
    - mode: 755
    - user: root
    - group: root
    - source: salt://spryker/files/jenkins_instance/etc/init.d/jenkins
    - template: jinja
    - context:
      environment: {{ environment }}

# Service configuration
/etc/default/jenkins-{{ environment }}:
  file.managed:
    - mode: 644
    - user: root
    - group: root
    - source: salt://spryker/files/jenkins_instance/etc/default/jenkins
    - template: jinja
    - context:
      environment: {{ environment }}
      environment_details: {{ environment_details }}
      settings: {{ settings }}

# Service
jenkins-{{ environment }}:
  service.running:
    - enable: True
    - require:
      - file: /etc/default/jenkins-{{ environment }}
      - file: /etc/init.d/jenkins-{{ environment }}

{%- endmacro %}
