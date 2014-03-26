kernel_install:
  match: '*'
  sls:
    - system.kernel_install

prepare-system:
  match: '*'
  require:
    - kernel_install
  sls:
    - system.minion
    - system.filesystems
