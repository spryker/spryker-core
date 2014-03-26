kernel:
  version: 3.12
  repository: wheezy-backports

filesystems:
  data:
    disk: /dev/xvde
    partition: 1
    filesystem: ext4
    mount_point: /data
    mount_options: noatime,nobarrier
# This example shows how to add volumes on specific machines - volumes must be first created in cloud admin panel!
{% if grains.id == 'some-wired-id-of-machine' %}
  data_elasticsearch:
    disk: /dev/xvdf
    partition: 1
    filesystem: ext4
    mount_point: /data/data_elasticsearch
    mount_option: noatime,nobarrier
{% endif %}

# Note - swapspace must be on ext3/ext4/xfs partitions, btrfs doesn't support swapspaces
swap:
  /SWAP:
    size: 2048
