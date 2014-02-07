#!/bin/bash

# Settings
RACKSPACE_API_USERNAME="fabianwesner"
RACKSPACE_API_KEY="14d002abd595109f2f383b8287098a00"
DOMAIN_NAME="project-boss.net"
RACKSPACE_REGION="LON"
RACKSPACE_API_URL="https://lon.identity.api.rackspacecloud.com/v2.0/tokens"

set -e
echo "###"
echo "### Instaling SaltStack master on this host"
echo "###"

# Allow access via ssh to root account
key="ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEAu6B/194wiz1Phd1tGGnlPJaFwHUm+Fyc2Ku8mMuPIodwqLjTa+ZZ3lhOmxHgO2VTeC/46p7HprlatSWBiS3rm28HW3tM0wLyxazUNN5xmUjRuYRun7IGlo9Q9BvBMdgNTZ464DWPbidRqHFoYG6Qh8+Tt2orEc/YcwKLzkjcvRYWuFRsf0yQr25Ouoweq+hXEetYPn67yWNndqfzBOvPDAYKcLy2rvnLNlE0GSlD52dLJ3uPFLa7IGlg9uI0wW9shyeLy04P+197rqRoMkeMHRrvgBIud3Z8Xz0nOxEivD+nFXnpaV4wHxEPaViWhuFXvRrsSltDU7+jGyrJbV5GpQ== elephant@punx.pl"
mkdir -p /root/.ssh
if [ ! -f /root/.ssh/authorized_keys ]; then
  echo "$key" > /root/.ssh/authorized_keys
fi

# Prepare apt repositories, install packages
echo "deb http://debian.saltstack.com/debian wheezy-saltstack main" > /etc/apt/sources.list.d/saltstack.list
wget -q -O- "http://debian.saltstack.com/debian-salt-team-joehealy.gpg.key" | apt-key add -
apt-get -qq update
apt-get -qq install salt-master python-pip

# Create code directory
mkdir -p /srv/salt

# Open salt-master firewall ports
ufw allow 4505/tcp
ufw allow 4506/tcp

# Install python extensions for rackspace cloud
pip -q install rackspace-novaclient salt-cloud apache_libcloud

# Prepare for cloud credentials
mkdir -p /etc/salt/cloud.providers.d
[ -f /etc/salt/cloud.providers.d/rackspace.conf ] || cat > /etc/salt/cloud.providers.d/rackspace.conf << EOF
marconi-test:
  minion:
    master: salt.${DOMAIN_NAME}
  identity_url: '${RACKSPACE_API_URL}'
  compute_name: cloudServersOpenStack
  protocol: ipv4
  compute_region: ${RACKSPACE_REGION}
  user: ${RACKSPACE_API_USERNAME}
  apikey: ${RACKSPACE_API_KEY}
  provider: openstack
EOF

[ -f /root/nova-credentials ] || cat > /root/nova-credentials << EOF
export OS_AUTH_URL=${RACKSPACE_API_URL}
export OS_AUTH_SYSTEM=rackspace
export OS_REGION_NAME=${RACKSPACE_REGION}
export OS_USERNAME=${RACKSPACE_API_USERNAME}
export OS_TENANT_NAME=${RACKSPACE_API_USERNAME}
export NOVA_RAX_AUTH=1
export OS_PASSWORD=${RACKSPACE_API_KEY}
export OS_PROJECT_ID=${RACKSPACE_API_USERNAME}
export OS_NO_CACHE=1
EOF

mkdir -p /etc/salt/cloud.profiles.d
[ -f /etc/salt/cloud.profiles.d/marconi.conf ] || cat > /etc/salt/cloud.profiles.d/marconi.conf << EOF
marconi-test-1GB:
  provider: marconi-test
  size: 1 GB Performance
  image: Debian 7 (Wheezy)

marconi-test-2GB:
  provider: marconi-test
  size: 2 GB Performance
  image: Debian 7 (Wheezy)

marconi-test-4GB:
  provider: marconi-test
  size: 4 GB Performance
  image: Debian 7 (Wheezy)

marconi-test-8GB:
  provider: marconi-test
  size: 8 GB Performance
  image: Debian 7 (Wheezy)
EOF

# Generate ssh keypair
if [ ! -f /root/.ssh/id_rsa ]; then
	ssh-keygen -f /root/.ssh/id_rsa -P ''
fi

# Upload key pair
source /root/nova-credentials
nova keypair-add --pub-key /root/.ssh/id_rsa.pub marconi
