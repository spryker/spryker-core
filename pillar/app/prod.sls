network:
  loadbalancers_interface: eth1
  project_interface: eth2

newrelic:
  license_key: 769f3d671612d86ce2a72fafe14a65103f43818f
  api_key: 
  appname: Demo Shop

solr:
  version: 4.6.1
  source: http://apache.mirrors.pair.com/lucene/solr/4.6.1/solr-4.6.1.tgz

environments:
  production:
    database:
      zed:
        hostname: 28c61f41c4b7d139868cb9190e557f3e3c9a7cce.rackspaceclouddb.com
        username: production
        password: pjbO7aSUm0
    static:
      hostname: FIXME-cdn-1234.hostname.project-yz.com
    tomcat:
      port_suffix: 5007
      min_heap_size: 512m
      max_heap_size: 1536m
      max_perm_size: 256m
    solr:
      lb_hostname: 10.189.246.59
    queue:
      stomp_port: 45006
    files:
      provider: rackspace
      api_username: FIXME
      api_key: FIXME
    stores:
      DE:
        yves:
          port: 15000
          hostnames:
            - www-production.project-boss.net
        zed:
          port: 15001
          hostname: zed-production.project-boss.net
#          htpasswd_file: /etc/nginx/htpasswd-zed
        dwh:
          hostname: dwh-production.project-boss.net
  staging:
    database:
      zed:
        hostname: 28c61f41c4b7d139868cb9190e557f3e3c9a7cce.rackspaceclouddb.com
        username: staging                                
        password: tkVM0EysbN64
    static:
      hostname: FIXME-cdn-1234.hostname.project-yz.com
    tomcat:
      port_suffix: 3007
      min_heap_size: 256m
      max_heap_size: 768m
      max_perm_size: 256m
    solr:
      lb_hostname: 10.189.246.66
    queue:
      stomp_port: 43006
    files:
      provider: rackspace
      api_username: FIXME
      api_key: FIXME
    stores:
      DE:
        yves:
          port: 13000
          hostnames:
            - www-staging.project-boss.net
          htpasswd_file: /etc/nginx/htpasswd-staging
        zed:
          port: 13001
          hostname: zed-staging.project-boss.net
          htpasswd_file: /etc/nginx/htpasswd-staging
        dwh:
          hostname: dwh-staging.project-boss.net


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

