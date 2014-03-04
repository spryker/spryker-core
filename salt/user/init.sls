{% for username, user in pillar.get('user', {}).items() %}

{{ username }}:
  user.present:
    - fullname: {{ user.fullname }}
    - shell: user.shell
    - groups:
      - adm
{% if user.ssh_key is defined %}
  ssh_auth:
    - present
    - user: {{ username }}
    - name: {{ user.ssh_key }}
    - require:
      - user: {{ username }}
{% endif %}
{% endfor %}
