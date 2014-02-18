network:
  loadbalancers_interface: lo
  project_interface: lo

newrelic:
  license_key:
  api_key:

environments:
  development:
    database:
      zed:
        hostname: localhost
        username: development
        password: mate20mg
    files:
      provider: rackspace
      api_username: xxx
      api_key: xxx
    stores:
      DE:
        yves:
          hostnames:
            - www-development.project-boss.net
        zed:
          hostname: zed-development.project-boss.net
          htpasswd_file:
        dwh:
          hostname: dwh.project-boss.net

  testing:
    database:
      zed:
        hostname: localhost
        username: development
        password: mate20mg
    files:
      provider: rackspace
      api_username: xxx
      api_key: xxx
    stores:
      DE:
        yves:
          hostnames:
            - www-development.project-boss.net
        zed:
          hostname: zed-development.project-boss.net
          htpasswd_file:
        dwh:
          hostname: dwh.project-boss.net
