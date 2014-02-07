{% for username, user in pillar.get('user', {}).items() %}

{{ username }}:
  user.present:
    - fullname: {{ user.fullname }}
    - shell: /bin/bash
    - groups:
      - adm
  ssh_auth:
    - present
    - user: {{ username }}
    - name: {{ user.ssh_key }}
    - require:
      - user: {{ username }}
{% endfor %}
