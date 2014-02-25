user:

  vagrant:
    fullname: Vagrant User

  root:
    fullname: Root Account
    ssh_key: |
      ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC6ksMZWDFQnHqZm+vH+mscRb9/bJt5BtJ1IfTtpjbJQ40m6AbfJ3GETbBb1Dq5k26AxTRq7ilBEUZbc53ujRwXKwkN08Dp5ZmiSea6mfbxg2rE8SHomi95p4ph0cd9kOaKx7NALj1YeWGPFn1jOUPtVmVqOSMw11LFgpmRxlIv3146gbYxRqCPPaET1Jm3ktJwSX42AeReuZYnAqPXNWrrIkLJ7GHW4fa5dH4ZKBPK/aZwLmaEJJUFK9+7kbgO7R+HJOXoAY2pmpSnwsv0+9YLKxL6G4KB0IuLXBi64R4QHvT6LbYprhTMzqXXVd+yu3+04k23YoHyv/uOQmo2Len7 root@pyz-vagrant

/root/.ssh/id_rsa:
  file.managed:
    - source: salt://user/files/dev/is_rsa
