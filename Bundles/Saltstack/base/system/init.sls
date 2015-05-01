#
# This is the first, base state, which is preparing basic operating system
# setup, like debian repositories, time configuration, sudoers, install basic
# utility packages/editors, configure salt minion.
#
# No spryker-specific logic should be included here.
#

include:
  - .repositories
  - .minion
  - .utils
  - .sudoers
  - .time
  - .sysctl
  - .motd
