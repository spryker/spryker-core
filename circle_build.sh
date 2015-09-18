#!/bin/bash

trigger_build_url=https://circleci.com/api/v1/project/spryker/demoshop/tree/develop?circle-token=111f68d96a7935d70d649d19776e9daffef9a9d1

branch=$1

post_data=$(cat <<EOF
{
  "build_parameters": {
    "RUN_NIGHTLY_BUILD": "true",
    "SPRYKER_BRANCH": "${branch}"
  }
}
EOF
)

curl --header "Accept: application/json" --header "Content-Type: application/json" --data "${post_data}" --request POST ${trigger_build_url}


