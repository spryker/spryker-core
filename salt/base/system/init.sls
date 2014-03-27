include:
  - .repositories
  - .filesystems
  - .minion
  - .utils
  - .sudoers
  - .vim
  - .time
  - .firewall
  - .sysctl
{%- if 'dev' not in grains.roles -%}
  - .rackspace
{%- endif -%}
