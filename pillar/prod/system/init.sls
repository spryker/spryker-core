filesystems:
  data:
    disk: /dev/xvde
    partition: 1
    filesystem: btrfs
    mount_point: /data
    mount_options: noatime,nobarrier,compress=zlib

swap:
  /data/_SWAP:
    size: 2048
