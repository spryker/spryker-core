# !!! Warning: after changing version here, running OverState will do kernel upgrade on all machines at the same time,
# rebooting all servers at the same time. To avoid it, use salt '...' state.sls system.kernel_upgrade (one host at a time)
kernel:
  version: 3.13-0.bpo.1-amd64
  repository: wheezy-backports

# Used for Rackspace Monitoring Agent
rackspace:
  username: projectaventure
  apikey: 5bacf8c555ebd9a929b1880fa605beb2


# Format filesystems - required when using additional block devices (in instances bigger than 1GB it's the case).
#
# For stable ext4, use:
#   filesystem: ext4
#   mount_option: noatime,nobarrier
#
# For btrfs filesystem with copy-on-write and compression (better performance!)
# (considered experimental, runs pretty stable with latest kernel = 3.13-0.bpo.1-amd64), use: 
#  filesystem: btrfs
#  mount_options: noatime,nobarrier,compress=lzo

filesystems:
  data:
    disk: /dev/xvde
    partition: 1
    mount_point: /data
    filesystem: btrfs
    mount_options: noatime,nobarrier,compress=lzo
# This example shows how to add volumes on specific machines - volumes must be first created in cloud admin panel!
{% if grains.id == 'some-wired-id-of-machine' %}
  data_elasticsearch:
    disk: /dev/xvdf
    partition: 1
    mount_point: /data/data_elasticsearch
    filesystem: ext4
    mount_option: noatime,nobarrier
{% endif %}

# Swapspace - there should be at least 2GB, even more on machines with big memory usage!
# Note - swapspace must be on ext3/ext4/xfs partitions, btrfs doesn't support it
swap:
  /SWAP:
    size: 2048
