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

    if [[ -d "Bundles/$module/src/" ]]; then
        SRC_FOLDER="Bundles/$module/src/"
    fi

    if [ -d "Bundles/$module/tests/" ]; then
        TESTS_FOLDER="Bundles/$module/tests/"
    fi

    php -d memory_limit=1300M vendor/bin/phpcs Bundles/$module --standard=$RULESET -p $SRC_FOLDER $TESTS_FOLDER
}

validateModuleCodeSniffer() {
  MODULES=$(git -C ./ diff --name-only --diff-filter=ACMRTUXB master... | grep "^Bundles\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

  echo "code sniffer check"
  for module in $MODULES
      do
          echo $2.$module

          local RULESET="Bundles/$module/phpcs.xml"
          if [ -f "$RULESET" ]; then
              output=$(runCommand $module $RULESET)

              if [ $? -ne 0 ]; then
                  echo $output
                  EXITCODE=1
              fi

              continue
          fi

          LEVEL=$(getConfigurationOption Bundles/$module/tooling.yml code-sniffer level)
          if [ -z "$LEVEL" ]; then
              LEVEL=1
          fi

          local RULESET="Bundles/Development/rulesetStrict.xml"
          if [ $LEVEL == 1 ]; then
              RULESET="Bundles/Development/ruleset.xml"
          fi

          output=$(runCommand $module $RULESET)
          if [ $? -ne 0 ]; then
              echo $output
              EXITCODE=1
          fi
      done
  wait
}

validateModuleCodeSniffer Spryker

exit $EXITCODE
