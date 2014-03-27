#!/bin/bash
###
# Use this script to create infrastructure, as specified in map file
# Option -d causes the script to delete infrastructure (dangerous!)
###

set -e

source /root/nova-credentials

# Check for parameters
if [ -z $1 ]; then
  echo "Usage: $1 <map_name> [-d]"
  exit 1
fi

if [ ! -f /srv/maps/$1.yaml ]; then
  echo "Map file: /srv/maps/$1.yaml not found!"
  exit 2
fi

# Call salt-cloud
date=`date +"%Y%m%d-%H%M%S"`
outfile="/tmp/salt-cloud-${date}.yaml"
time salt-cloud --out-file=${outfile} -m /srv/maps/$1.yaml -P $2
echo "salt-cloud finished - output saved in ${outfile}"

# Display IP addresses of running instances
sleep 10s
salt -G 'deployment:prod' cmd.run 'wget -qO- http://ipecho.net/plain'

echo "----------"
echo "Finished."
echo "To prepare kernel and filesystems on new instances, you should run now:"
echo "salt-run state.over base"
echo "----------"
