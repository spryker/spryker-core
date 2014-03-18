#!/bin/bash
# SSH wrapper script
# The path to this script is passed as GIT_SSH environmental variable during deployment. 
# It forces ssh to use custom private key, it this case - /etc/deploy/deploy.key
# The appropiate public key has to be allowed in git repository.


[ -O /tmp/ssh_agent ] && eval `cat /tmp/ssh_agent` &> /dev/null
ssh -i /etc/deploy/deploy.key $1 $2