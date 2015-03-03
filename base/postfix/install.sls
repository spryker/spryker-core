#
# Install postfix and its dependencies
#

postfix:
  pkg:
    - installed
  service.running:
    - require:
      - pkg: postfix

procmail:
  pkg.installed
