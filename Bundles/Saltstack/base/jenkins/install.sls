#
# Install package, remove default service
#

jenkins:
  pkg:
    - installed
  service:
    - dead
    - enable: False
    - require:
      - pkg: jenkins

# Make sure that www-data can unpack jenkins war file
/var/cache/jenkins:
  file.directory:
    - user: www-data
    - group: www-data
    - mode: 775
    - recurse:
      - user
      - group
    - require:
      - pkg: jenkins
