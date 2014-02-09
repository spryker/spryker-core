network:
  project_interface: eth2

newrelic:
  license_key:
  api_key:

environments:
  production:
    database:
      zed:
        hostname: xxx
        username: xxx                                
        password: xxx
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

