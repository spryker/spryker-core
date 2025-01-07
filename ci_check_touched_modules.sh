#!/usr/bin/env bash

EXITCODE=0

getConfigurationOption() {
   local tool=$2
   local option=$3
   local space='[[:space:]]*' key='[a-zA-Z0-9_]*' fs=$(echo @|tr @ '\034')
   sed -ne "s|^\($space\)\($key\)$space:$space\"\(.*\)\"$space\$|\1$fs\2$fs\3|p" \
        -e "s|^\($space\)\($key\)$space:$space\(.*\)$space\$|\1$fs\2$fs\3|p"  $1 |
   awk -F$fs '{
      indent = length($1)/2;
      vname[indent] = $2;
      for (i in vname) {if (i > indent) {delete vname[i]}}
         if (length($3) > 0 && "'$option'" == $3) {
            printf("%s\n", $value);
         }
   }'
}

runCommand() {
    local SRC_FOLDER=''
    local TESTS_FOLDER=''

    if [[ -d "$module_directory/$module/src/" ]]; then
        SRC_FOLDER="$module_directory/$module/src/"
    fi

    if [ -d "$module_directory/$module/tests/" ]; then
        TESTS_FOLDER="$module_directory/$module/tests/"
    fi

    php -d memory_limit=-1 vendor/bin/phpcs $module_directory/$module --standard=$RULESET -p $SRC_FOLDER $TESTS_FOLDER
}

validateModuleCodeSniffer() {
  local module_directory=$2
  MODULES=$(git -C ./ diff --name-only --diff-filter=ACMRTUXB master... | grep "^$module_directory\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

  echo "code sniffer check"
  for module in $MODULES
      do
          echo $1.$module

          if [[ ! -d "$module_directory/$module/src/" && ! -d "$module_directory/$module/tests/" ]]; then
             continue
          fi

          local RULESET="$2/$module/phpcs.xml"
          if [ -f "$RULESET" ]; then
              output=$(runCommand $module $RULESET $module_directory)

              if [ $? -ne 0 ]; then
                  echo "${output}"
                  EXITCODE=1
              fi

              continue
          fi

          LEVEL=$(getConfigurationOption $2/$module/tooling.yml code-sniffer level)
          if [ -z "$LEVEL" ]; then
              LEVEL=1
          fi

          local RULESET="Bundles/Development/rulesetStrict.xml"
          if [ $LEVEL == 1 ]; then
              RULESET="Bundles/Development/ruleset.xml"
          fi

          output=$(runCommand $module $RULESET $module_directory)
          if [ $? -ne 0 ]; then
              echo "${output}"
              EXITCODE=1
          fi
      done
  wait
}

validateModuleCodeSniffer Spryker Bundles
validateModuleCodeSniffer Spryker Features

exit $EXITCODE
