#!/bin/bash

set -e
root_dir="$1"
test_namespace="$2"

if [[ ! -d "$root_dir" ]]; then
  echo "Specify an existing folder with modules"
  exit 1
fi
if [[ -z "$test_namespace" ]]; then
  echo "Specify a testing namespace"
  exit 1
fi

modules=()
for dir in "$root_dir"/*/; do
    yml_file="$dir/codeception.yml"
    module_path=$(dirname "$yml_file")
    module_name=$(basename "$module_path")

    if [[ -f "$yml_file" ]]; then
        layerNames=("Zed" "Glue" "Client" "Shared" "Yves")
        for layerName in "${layerNames[@]}"; do
            if [[ -f "$module_path/tests/$test_namespace/$layerName/$module_name/codeception.yml" ]]; then
                if  ! grep -qE "tests/$test_namespace/$layerName/$module_name" "$yml_file" && \
                    ! grep -qE "tests/$test_namespace/.*/$module_name" "$yml_file"; then
                    modules+=("$module_name")
                fi
            fi
        done
    fi
done
RED='\033[0;31m'
NC='\033[0m'

if [ -n "$modules" ]; then
    echo -e "${RED}"
    echo "Needs to update include section in module codeception.yml for:" >&2
    printf "%s\n" "${modules[@]}" | sort | uniq
    echo -e "${NC}"
    exit 1
fi

# Check modules without tests
#echo "Modules doesn't have any tests:"
#for dir in "$root_dir"/*/; do
#    yml_file="$dir/codeception.yml"
#    module_path=$(dirname "$yml_file")
#    module_name=$(basename "$module_path")
#
#    if [[ -f "$yml_file" ]]; then
#        continue
#    else
#        if [[ "$module_name" != *Extension ]]; then
#            echo "$module_name"
#        fi
#    fi
#done

