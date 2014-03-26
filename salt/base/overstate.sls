upgrade-kernel:
  match: '*'
  sls:
    - system.kernel_upgrade

prepare-system:
  match: '*'
  require:
    - upgrade-kernel
  sls:
    - system.minion
    - system.filesystems
