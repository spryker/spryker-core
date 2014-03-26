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

provision-data-nodes:
  match: 'data*'
  require:
    - prepare-system

provision-all-nodes:
  match: '*'
  require:
    - provision-data-nodes
