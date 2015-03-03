#
# Update postgresql package
#
# Note: this state is included only if pillar setting autoupdate:postgresql is true

update-postgresql:
  pkg.latest:
    - name: postgresql-9.4
