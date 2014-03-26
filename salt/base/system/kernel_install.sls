# This state will ensure that backports kernel is installed and should be executed just after provisioning VM's (by Overstate).
# After new kernel is upgraded, the machine will be rebooted.

linux-image-amd64:
  pkg.installed:
{%- set repository = salt['pillar.get']('kernel_repository', '') %}
{%- if repository != '' %}
    - fromrepo: {{ pillar.kernel.}}
{%- endif %}

shutdown -r now && sleep 10m:
  cmd.wait:
    - watch:
      - pkg: linux-image-amd64
