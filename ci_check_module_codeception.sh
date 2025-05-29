#!/bin/bash

set -e
root_dir="$1"

if [[ ! -d "$root_dir" ]]; then
  echo "Specify an existing folder with modules"
  exit 1
fi

echo "🔍 Check module codeception.yml под $root_dir"

modules=()
for dir in "$root_dir"/*/; do
    yml_file="$dir/codeception.yml"
    module_path=$(dirname "$yml_file")
    module_name=$(basename "$module_path")

    if [[ -f "$yml_file" ]]; then
        if [[ -d "$module_path/tests/SprykerTest/Zed/codeception.yml" ]]; then
            if ! grep -qE "tests/SprykerTest/Zed/$module_name" "$yml_file"; then
                modules+=("$module_name")
            fi
        fi
        if [[ -d "$module_path/tests/SprykerTest/Glue/codeception.yml" ]]; then
            if ! grep -qE "tests/SprykerTest/Glue/$module_name" "$yml_file"; then
                modules+=("$module_name")
            fi
        fi
        if [[ -d "$module_path/tests/SprykerTest/Client/codeception.yml" ]]; then
            if ! grep -qE "tests/SprykerTest/Client/$module_name" "$yml_file"; then
                modules+=("$module_name")
            fi
        fi
        if [[ -d "$module_path/tests/SprykerTest/Shared/codeception.yml" ]]; then
            if ! grep -qE "tests/SprykerTest/Shared/$module_name" "$yml_file"; then
                modules+=("$module_name")
            fi
        fi
    fi
done
RED='\033[0;31m'
NC='\033[0m'

if [ -n "$modules" ]; then
    echo -e "${RED}"
    echo "Update include section in module codeception.yml for:" >&2
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

