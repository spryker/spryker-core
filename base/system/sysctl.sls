#
# Linux kernel tuning
#

# Unix socket connection backlog size
net.core.somaxconn:
  sysctl.present:
    - value: 4096

# Minimize disk swapping
vm.swappiness:
  sysctl.present:
    - value: 5

# Make sure that redis bgsave can overcommit virtual memory
vm.overcommit_memory:
  sysctl.present:
    - value: 1
