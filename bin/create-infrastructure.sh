#!/bin/bash
###
# Use this script to create infrastructure, as specified in map file
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

date=`date +"%Y%m%d-%H%M%S"`
outfile="/tmp/salt-cloud-${date}.yaml"
time salt-cloud --out-file=${outfile} -m /srv/maps/$1.yaml -P $2

echo "salt-cloud finished - output saved in ${outfile}"

sleep 10s
salt '*' test.ping
