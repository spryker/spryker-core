# GIT repository for getting the code source
deploy:
  git_url: git@github.com:spryker/demoshop.git
  git_hostname: github.com
#  git_url: git@codebasehq.com:project-a/core/pyz.git
#  git_hostname: codebasehq.com

# Autoupdate mechanism updates packages automatically on each salt run, if
# it's enabled here. If this setting is disabled (recommended for production),
# then packages can be updated by running state <state>.update, for example:
# salt '*' state.sls elasticsearch.update
autoupdate:
  nodejs: true
  elasticsearch: true
  php: true
  mysql: true
  postgresql: true
  rabbitmq: true

elasticsearch:
  # Elasticsearch major version. Optional, default: 1.4
  version: 1.4

logstash:
  # Logstash major version. Optional, default: 1.4
  version: 1.4

# List of stores. Note, each store defined here should be configured within
# each environment section
stores:
  - DE
#  - US

php:
  # PHP debugger / profiler. Enable only on local or QA environment, never
  # use them on production.
  # Default: xdebug is enabled only if role 'dev' is included
  # xhprof is disabled
  #enable_xdebug: true
  #enable_xhprof: true

  # PHP OpCache. Default: enabled
  #enable_opcache: true

# Newrelic credentials - leave empty for non-production setups
newrelic:
  license_key:
  api_key:
  appname:

# Configuration of environments
# It can consist of any subset of {development,testing,staging,production}
environments:
  development:
    database:
      # Zed database credentials - for both MySQL and PostgreSQL
      zed:
        hostname: localhost
        username: development
        password: mate20mg
    elasticsearch:
      # JVM Heap Size of Elasticsearch. Optional, Default: 384m
      heap_size: 384m
    redis:
      host: 127.0.0.1
      port: ''
    static:
      # Enable local static files virtual host in nginx?
      # Optional, Default: false
      enable_local_vhost: true
      # Hostname for local static files virtual host in nginx,
      # Required if enable_local_vhost is set to true, no default value
      hostname: static-development.project-yz.com
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
            http: http://static-development.project-yz.de
            https: https://static-development-secure.project-yz.de
          # US:
          #   http: http://static-development.project-yz.com
          #   https: https://static-development-secure.project-yz.com
        static_assets:
          DE:
            http: http://static-development.project-yz.de
            https: https://static-development-secure.project-yz.de
          # US:
          #   http: http://static-development.project-yz.com
          #   https: https://static-development-secure.project-yz.com
        delete_local_processed_images: true
        delete_original_images: true
    stores:
      DE:
        yves:
          hostnames:
            - www-development.project-yz.de
        zed:
          hostname: zed-development.project-yz.de
          # Optional: path to htpasswd file. Comment out to disable http auth
          # htpasswd_file:
      # US:
      #   yves:
      #     hostnames:
      #       - www-development.project-yz.com
      #   zed:
      #     hostname: zed-development.project-yz.com
      #     # Optional: path to htpasswd file. Comment out to disable http auth
      #     # htpasswd_file:
  testing:
    database:
      zed:
        hostname: localhost
        username: testing
        password: mate20mg
    elasticsearch:
      heap_size: 384m
    redis:
      host: 127.0.0.1
      port: ''
    static:
      # Enable local static files virtual host in nginx?
      # Optional, Default: false
      enable_local_vhost: true
      # Hostname for local static files virtual host in nginx,
      # Required if enable_local_vhost is set to true, no default value
      hostname: static-development.project-yz.com
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
            http: http://static-testing.project-yz.de
            https: https://3963947472a6621adbcb-fb198443397976013dfa73b29d41f433.ssl.cf3.rackcdn.com
          # US:
          #   http: http://static-testing.project-yz.com
          #   https: https://61862c85d035b0cdd7b8-73dfe4b3babcf6d381f8fa0527f882da.ssl.cf3.rackcdn.com

        static_assets:
          DE:
            http: http://static-testing.project-yz.de
            https: https://3963947472a6621adbcb-fb198443397976013dfa73b29d41f433.ssl.cf3.rackcdn.com
          # US:
          #   http: http://static-testing.project-yz.com
          #   https: https://61862c85d035b0cdd7b8-73dfe4b3babcf6d381f8fa0527f882da.ssl.cf3.rackcdn.com
        delete_local_processed_images: true
        delete_original_images: true
    stores:
      DE:
        yves:
          hostnames:
            - www-testing.project-yz.de
        zed:
          hostname: zed-testing.project-yz.de
          #htpasswd_file:
      # US:
      #   yves:
      #     hostnames:
      #       - www-testing.project-yz.com
      #   zed:
      #     hostname: zed-testing.project-yz.com
      #     #htpasswd_file:

# The key below is used for deployment - from deployment server user root must be able to log in to all other
# servers as user root.
# If we're using salt-cloud to create cloud VM's - it will automatically generate /root/.ssh/id_rsa
# on salt master and copy appropiate id_rsa.pub to minions to /root/.ssh/authorized_keys.
#
# Paste the content of /root/.ssh/id_rsa from salt-master here:
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
