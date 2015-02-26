#
# Update nodejs package
#
# Note: this state is included only if pillar setting autoupdate:nodejs is true

update-nodejs:
  pkg.latest:
    - name: nodejs
