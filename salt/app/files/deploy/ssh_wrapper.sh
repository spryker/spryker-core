#!/bin/bash
# SSH wrapper script
# This script is passed as GIT_SSH during deployment. It forces ssh to use custom private key.
# The appropiate public key has to be allowed in git repository.
#

[ -O /tmp/ssh_agent ] && eval `cat /tmp/ssh_agent` &> /dev/null
ssh -i /etc/deploy/deploy.key $1 $2
