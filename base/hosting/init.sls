#
# Hoster-dependant settings
# If we have a hosting pillar item set up, then we include the state here
#

include:
  - .{{ pillar.hosting.provider }}
