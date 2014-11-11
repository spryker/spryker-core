vm.swappiness:
  sysctl.present:
    - value: 10

net.core.somaxconn:
  sysctl.present:
    - value: 4096

# increases redis performance
vm.overcommit_memory:
  sysctl.present:
    - value: 1