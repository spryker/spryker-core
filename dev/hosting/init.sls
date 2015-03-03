hosting:

  # Name of the sls file in hosting state
  # Mandatory, no default value
  provider: vagrant

  # Country of debian mirror to use for installing packages
  # Optional, defaults to: de
  debian_mirror: de

  # Network part of created MySQL users
  # Optional, defaults to: %
  mysql_network: "%"

  # Network allowed for PostgreSQL access (in pg_hba.conf)
  # Optional, default: none
  postgresql_network: 10.0.0.0/8

  # Network interface used for communication between spryker components
  # Mandatory, default: lo (works on localhost only)
  #project_network_interface: eth0

  # List of whitelisted IP's for HTTP authorization
  # It should include local IP addresses or networks of Yves/Zed servers
  # HTTP API requests between Yves and Zed must be whitelisted!
  # Optional, defaults to: - 127.0.0.1
  http_auth_whitelist:
    - 127.0.0.1/32
    - 10.10.0.0/24
