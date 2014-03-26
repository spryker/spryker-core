kernel_install:
  match: '*'
  sls:
    - system.repositories
    - system.kernel_install

prepare-system:
  match: '*'
  require:
    - kernel_install
  sls:
    - system.minion
    - system.filesystems

highstate-on-all-nodes:
  match: '*'
  require:
    - prepare-system
