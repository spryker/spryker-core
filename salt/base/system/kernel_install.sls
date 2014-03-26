# This state will ensure that backports kernel is installed and should be executed just after provisioning VM's (by Overstate).
# After new kernel is upgraded, the machine will be rebooted.
{%- set version = salt['pillar.get']('kernel:version', '') %}
{%- set repository = salt['pillar.get']('kernel:repository', '') %}

{%- if version != '' %}
linux-image-{{ version }}:
  pkg.installed:
{%- if repository != '' %}
    - fromrepo: {{ repository }}
{%- endif %}

shutdown -r now:
  cmd.wait:
    - watch:
      - pkg: linux-image-{{ version }}

sleep 10m:
  cmd.wait:
    - watch:
      - pkg: linux-image-{{ version }}

{%- endif %}