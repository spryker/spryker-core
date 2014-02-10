network:
  loadbalancers_interface: eth1
  project_interface: eth2

newrelic:
  license_key:
  api_key:

environments:
  production:
    database:
      zed:
        hostname: 28c61f41c4b7d139868cb9190e557f3e3c9a7cce.rackspaceclouddb.com
        username: production                                
        password: pjbO7aSUm0
    files:
      provider: rackspace
      api_username: xxx
      api_key: xxx
    stores:
      DE:
        yves:
          hostnames:
            - www.project-boss.net
        zed:
          hostname: zed.project-boss.net
          htpasswd_file: /etc/nginx/htpasswd-zed
        dwh:
          hostname: dwh.project-boss.net

