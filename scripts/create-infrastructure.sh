#!/bin/bash
###
# Use this script to create infrastructure, as specified in map file
###

set -e

source /root/nova-credentials

# Check for parameters
if [ -z $1 ]; then
  echo "Usage: $1 <map_name>"
  exit 1
fi

if [ ! -f /srv/maps/$1.yaml ]; then
  echo "Map file: /srv/maps/$1.yaml not found!"
  exit 2
fi

time salt-cloud -m /srv/maps/$1.yaml -P
