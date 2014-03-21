filesystems:
  data:
    disk: /dev/xvde
    partition: 1
    filesystem: btrfs
    mount_point: /data
    mount_options: noatime,nobarrier,compress=zlib

swap:
  /SWAP:
    size: 2048
