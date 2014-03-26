#!/bin/bash
###
# Use this script on a freshly installed debian wheezy server to setup SaltStack Master
# This script assumes, that the salt git repostiory of the project has been cloned into /srv directory
# Before running, edit the settings below:
###

# Settings
RACKSPACE_API_USERNAME="projectaventure"
RACKSPACE_API_KEY="5bacf8c555ebd9a929b1880fa605beb2"
RACKSPACE_REGION="LON"
RACKSPACE_API_URL="https://lon.identity.api.rackspacecloud.com/v2.0"
DOMAIN_NAME="project-yz.com"
RACKSPACE_PROJECT_NET_UUID="423be9d4-dbb6-40ca-ae63-eb456d4ace8f"

# Implementation starts here

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

# Open salt-master firewall ports
ufw allow 4505/tcp
ufw allow 4506/tcp

# Workaround to get latest pip on wheezy
pip install --upgrade pip
ln -sf /usr/local/bin/pip /usr/bin/pip

# Install python extensions for rackspace cloud
pip -q install rackspace-novaclient salt-cloud apache_libcloud
pip install --upgrade distribute

# Default settings for salt-master
cat > /etc/salt/master << EOF
job_cache: True
state_output: mixed
state_events: False
log_level: warning
log_level_logfile: warning
file_roots:
  base:
    - /srv/salt/base
  dev:
    - /srv/salt/dev
    - /srv/salt/base
  qa:
    - /srv/salt/qa
    - /srv/salt/base
  prod:
    - /srv/salt/prod
    - /srv/salt/base
pillar_roots:
  base:
    - /srv/pillar/base
  dev:
    - /srv/pillar/dev
    - /srv/pillar/base
  qa:
    - /srv/pillar/qa
    - /srv/pillar/base
  prod:
    - /srv/pillar/prod
    - /srv/pillar/base
EOF

# Prepare cloud credentials
mkdir -p /etc/salt/cloud.providers.d
[ -f /etc/salt/cloud.providers.d/rackspace.conf ] || cat > /etc/salt/cloud.providers.d/rackspace.conf << EOF
prod-rackspace:
  minion:
    master: salt.${DOMAIN_NAME}
    environment: prod
  identity_url: '${RACKSPACE_API_URL}'
  compute_name: cloudServersOpenStack
  protocol: ipv4
  compute_region: ${RACKSPACE_REGION}
  user: ${RACKSPACE_API_USERNAME}
  apikey: ${RACKSPACE_API_KEY}
  provider: openstack
  ssh_key_name: master
  ssh_key_file: /root/.ssh/id_rsa
  networks:
    - fixed:
      - 00000000-0000-0000-0000-000000000000
      - 11111111-1111-1111-1111-111111111111
      - ${RACKSPACE_PROJECT_NET_UUID}

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
#source /root/nova-credentials

# Prepare cloud profiles (from git repo)
if [ ! -L /etc/salt/cloud.profiles.d ]; then
  [ -d /etc/salt/cloud.profiles.d ] && mv /etc/salt/cloud.profiles.d /etc/salt/cloud.profiles.bak
  ln -sf /srv/cloud.profiles.d /etc/salt/cloud.profiles.d
fi

# Setup utilities
salt-call -l error --file-root=/srv/salt/base --local state.sls system.master

# Start salt-master
/etc/init.d/salt-master restart

echo "Master setup successful!"

