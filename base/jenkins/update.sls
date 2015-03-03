#
# Update jenkins package
#
# Note: this state is included only if pillar setting autoupdate:jenkins is true

update-jenkins:
  pkg.latest:
    - name: jenkins
