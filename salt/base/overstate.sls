prepare-system:
  match: '*'
  sls:
    - system.minion
    - system.filesystems
