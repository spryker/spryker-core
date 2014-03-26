# This state is not included in highstate - it's executed manually during master setup
# Note - pillars are not available here
#
# Command to run it:
# salt-call -l error --file-root=/srv/salt/base --local state.sls system.repositories

include:
  - .repositories
  - .utils
  - .sudoers
  - .vim
  - .time
  - .firewall
  - .sysctl
