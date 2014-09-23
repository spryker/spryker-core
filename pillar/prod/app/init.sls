deploy:
  git_url: git@codebasehq.com:project-a/core/pyz.git
  git_hostname: codebasehq.com

jenkins:
  version: 1.532.2
  source: http://mirrors.jenkins-ci.org/war-stable/1.532.2/jenkins.war

network:
  loadbalancers_interface: eth1
  project_interface: eth2

newrelic:
  license_key: 769f3d671612d86ce2a72fafe14a65103f43818f
  api_key: 0710865a297806b3417533d11ec95978be35aa43f43818f
  appname: Demo Shop

# List enabled stores
stores:
  DE:
    locale: de_DE
    appdomain: '00'
  US:
    locale: en_US
    appdomain: '10'

# List enabled environments
environments:
  production:
    database:
      zed:
        hostname: c5a1dde99bbb1cc815296418cbf56da234d74e33.rackspaceclouddb.com
        username: production
        password: pjbO7aSUm0
    tomcat:
      min_heap_size: 128m
      max_heap_size: 384m
      max_perm_size: 128m
    elasticsearch:
      heap_size: 256m
    cloud:
      enabled: true
      object_storage:
        enabled: true
        rackspace:
          api_username: demoshop.cloudfiles
          api_key: a9d62990a9d74e6f88d3344555bd2a85
      cdn:
        enabled: true
        static_media:
          DE:
            http: http://static.project-yz.de
            https: https://d8c570a75c3f0172aa83-cafc7b6bb6aa6cd48eab4c1683373025.ssl.cf3.rackcdn.com
          US:
            http: http://static.project-yz.com
            https: https://280eec0e470d74a52757-9d50a0bd32e20509da2ab0e86faf9dfe.ssl.cf3.rackcdn.com

        static_assets:
          DE:
            http: http://static.project-yz.de
            https: https://d8c570a75c3f0172aa83-cafc7b6bb6aa6cd48eab4c1683373025.ssl.cf3.rackcdn.com
          US:
            http: http://static.project-yz.com
            https: https://280eec0e470d74a52757-9d50a0bd32e20509da2ab0e86faf9dfe.ssl.cf3.rackcdn.com
        delete_local_processed_images: true
        delete_original_images: true
    stores:
      DE:
        yves:
          hostnames:
            - www.project-yz.de
        zed:
          hostname: zed.project-yz.de
#          htpasswd_file: /etc/nginx/htpasswd-zed
        dwh:
          saiku_hostname: dwh.project-yz.de
      US:
        yves:
          hostnames:
            - www.project-yz.com
        zed:
          hostname: zed.project-yz.com
#          htpasswd_file: /etc/nginx/htpasswd-zed
        dwh:
          saiku_hostname: dwh.project-yz.com
  staging:
    database:
      zed:
        hostname: c5a1dde99bbb1cc815296418cbf56da234d74e33.rackspaceclouddb.com
        username: staging                                
        password: tkVM0EysbN64
    tomcat:
      min_heap_size: 128m
      max_heap_size: 256m
      max_perm_size: 128m
    elasticsearch:
      heap_size: 256m
    cloud:
      enabled: true
      object_storage:
        enabled: true
        rackspace:
          api_username: demoshop.cloudfiles
          api_key: a9d62990a9d74e6f88d3344555bd2a85
      cdn:
        enabled: true
        static_media:
          DE:
            http: http://static-staging.project-yz.de
            https: https://d3d22d4c9f2c4f411161-8fb13e9b852389e0543f2c0802d4997e.ssl.cf3.rackcdn.com
          US:
            http: http://static-staging.project-yz.com
            https: https://aabd028975c755cf7589-5eb80ff65465225f76de3b568e724ebe.ssl.cf3.rackcdn.com
        static_assets:
          DE:
            http: http://static-staging.project-yz.de
            https: https://d3d22d4c9f2c4f411161-8fb13e9b852389e0543f2c0802d4997e.ssl.cf3.rackcdn.com
          US:
            http: http://static-staging.project-yz.com
            https: https://aabd028975c755cf7589-5eb80ff65465225f76de3b568e724ebe.ssl.cf3.rackcdn.com
        delete_local_processed_images: true
        delete_original_images: true
    stores:
      DE:
        yves:
          hostnames:
            - www-staging.project-yz.de
          htpasswd_file: /etc/nginx/htpasswd-staging
        zed:
          hostname: zed-staging.project-yz.de
          htpasswd_file: /etc/nginx/htpasswd-staging
        dwh:
          saiku_hostname: dwh-staging.project-yz.de
      US:
        yves:
          hostnames:
            - www-staging.project-yz.com
          htpasswd_file: /etc/nginx/htpasswd-staging
        zed:
          hostname: zed-staging.project-yz.com
          htpasswd_file: /etc/nginx/htpasswd-staging
        dwh:
          saiku_hostname: dwh-staging.project-yz.com

# The key below is used for deployment - from deployment server user root must be able to log in to all other
# servers as user root.
# If we're using salt-cloud to create cloud VM's - it will automatically generate /root/.ssh/id_rsa
# on salt master and copy appropiate id_rsa.pub to minions to /root/.ssh/authorized_keys.
#
# Paste here the content of /root/.ssh/id_rsa from salt-master:
server_env:
  ssh:
    id_rsa: |
      -----BEGIN RSA PRIVATE KEY-----
      MIIEowIBAAKCAQEAxY2OQI67aFsKdSN9k0eeW9rv25zLsXkYQ8opOJmEs//b39Sh
      gaPXFjXB2LheCtpeQYJuhr0liaR8z900LTnkd5Ck8irYgRdI4wwDYrJStqGmi/W9
      0JBOSaSZKB5Bq4ZtmRzp2G2UxGG2Cp8Z4pvMzKi1AeyWCCh1y/dk+XCa2VUrffw3
      LNODRxGm5T3ks99ClOq/gczXPOJesHavYpguYJZ4xAyyCD3sgwVtDO6mz+/D7fMM
      p7qlM6+RyGUkoVTHep8GXxBLWpNL0m0rxIC8O8cWUdzRxXKuhTp3HJVcSBzluPZM
      LF8HgSYtrtuV/g49ogpDFpyRpP+o2kU+Z9ZMowIDAQABAoIBADhunGEipLRFtXIK
      xldpEPqKSxQ/8QvsXJsYx7esWvUUNJn24n/m7o0gpBQlbm/JOz2ZZbtFktCD1UeH
      l90HeZUSE5w90wTlQuPgzaLG78vN14aJl0RZxJNS2pFUO0wlJW+ki8RQYTvL8bWN
      B1btTI517ubyz1TbQfMW45hBq2pDYl3AMITFw9RPFhxWqX0LCbd2+E4Kb24Gucw8
      zGedD3jiHQQEDoWa8MzA3R27cu8h38/y3VuZwlqAH8LYZz3Pa3hzsPijR9Hu01Ti
      PRRU/gl8/6867PNcGNrPp+3KYzWiRnMkHKM0NUkLrjrmu282/+9RlcoIvE7HSQNa
      78WXi1ECgYEA7/HM6XQEri37InQgBMXCseHj0hdg3vYjwLyc49GScSEk4gE7osFt
      v3gnveQumPOc097oImm/YjMABTI0Adea0ZDQrzqoRcrl8Y/HZhQsHC3FzWPgjp4M
      NSGDxdsqkvvqxbucaTZKNjEvt+Foj9HwN1PgfRg8oTKRbn15k1iF78sCgYEA0sWY
      qWxrHB9MHZcbhmvF285vXNJS8+hqd3WZiy5vzCBeBajHiO3vt6o0pFyjHB18SkWR
      NToDqJK8gk3MBowZoxo2Izq4OuAIhEk3mPAl/XxijJL8rwqrZ4EpV8N8B6OqMw9T
      TGogBiWng3uUqKO8WIMQCVv/LIaQosWm9tKpy4kCgYAB3vHJFDWBIiymHR+uydf1
      yRwcpEAGHQc0VoQmppistyRxeq77WuS/LHGq4l+Wo7eiU0eeFRL/8gPbBoQvS34S
      ij2GUD/Fo4pdctCMNDYP1i+HpXK3yfWwaF19qnLPiJVRC2Bx1ZGIkKOdnG1TScDE
      vauvbgPzAdDuep37DyKkNwKBgGsfzMiHdyTy7z+21mGKkyR1lnop18lp3frbRW/M
      6TtpVAAyWT/KFuVVV64V+zzF667gDr5rvwJFmhPsMH8/Y6RXJR7gsFQUG6AIin5r
      yBM+zeO+rTjWBmnz5qxZicdkMpQC/QZAhgg5yRr1i4fuuEKQUm0/WcEqn4ZrSMH4
      Lh5pAoGBALcsa0Ih3XfeG/KhvkdrDiwreCn4h/78iMBRtkDE8X0/e/+OB+GNkR9b
      nK6pZcl5Cm0Exn6PRsiuhuqgZ7w2OcV4L3wld2Ro8aNsnTthqwe2TUsW0Bjzy+tx
      D2U7XJ1kDaA8iCexqsJmUGjSJ8p2wERuolPo0cGEEZq9c3g84FC6
      -----END RSA PRIVATE KEY-----

