#
# Update elasticsearch package
#
# Note: this state is included only if pillar setting autoupdate:elasticsearch is true

update-elasticsearch:
  pkg.latest:
    - name: elasticsearch
