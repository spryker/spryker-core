# This state will upgrade the kernel and reboot the machine. It's not included in highstate and should be called via overstate only.

linux-image-amd64:
  pkg.latest:
    - fromrepo: wheezy-backports

shutdown -r now:
  cmd.wait:
    - watch:
      - pkg: linux-image-amd64
