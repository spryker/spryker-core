# !!! Dangerous !!!
#
# This state will ensure that latest kernel is installed.
# After new kernel is upgraded, the machine will be rebooted.
# This state is NOT included in highstate and should be executed manually (salt '...' state.sls system.kernel_upgrade)
#
# It's generally good idea to execute it on one host at a time, as it will reboot the machine. So don't use salt '*' - or you will reboot the whole server farm
# and cause website offline for some time!

linux-image-amd64:
  pkg.latest:
{%- set repository = salt['pillar.get']('kernel_repository', '') %}
{%- if repository != '' %}
    - fromrepo: {{ pillar.kernel.}}
{%- endif %}

shutdown -r now && sleep 10m:
  cmd.wait:
    - watch:
      - pkg: linux-image-amd64
