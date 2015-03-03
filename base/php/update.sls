#
# Update PHP package
#
# Note: this state is included only if pillar setting autoupdate:php is true

# Update php packages. We have to specify here php5, php5-common (to force
# upgrading php extensions installed via debian packages) and php5-fpm
# (to workaround debian package system installing libapache2-mod-php5)
update-php:
  pkg.latest:
    - pkgs:
      - php5-fpm
      - php5-common
      - php5
      - php-pear
      - php5-dev
#    - watch_in:
#      - cmd: pe

# Update all installed PEAR and PECL extensions
update-pear:
  cmd.wait:
    - name: pear upgrade
    - watch_in:
      - cmd: update-pecl

update-pecl:
  cmd.wait:
    - name: pecl upgrade
