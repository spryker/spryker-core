dev:
  group.present:
    - system: true

{% for username, user in pillar.get('user', {}).items() %}

{{ username }}:
  user.present:
    - fullname: {{ user.fullname }}
    - groups:
      - dev
{% if (user.admin is defined) and user.admin %}
      - adm
{% endif %}

    - shell: {% if user.shell is defined %}{{ user.shell }}{% else %}/bin/bash{% endif %}

{% if user.ssh_key is defined %}
  ssh_auth:
    - present
    - user: {{ username }}
    - name: {{ user.ssh_key }}
    - require:
      - user: {{ username }}
{% endif %}
{% endfor %}
