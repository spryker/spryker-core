#
# Install Ruby 1.9 and used gems
#

ruby:
  pkg.installed:
    - pkgs:
      - ruby1.9.1
      - ruby-dev
      - libncurses5-dev
      - build-essential

compass:
  gem.installed

psych:
  gem.installed

highline:
  gem.installed:
    - required:
      - gem: psych


# Install fixed versions, as the 2.8.0+ had problems with changed packet sizes
net-ssh:
  gem.installed:
    - version: 2.7.0

net-scp:
  gem.installed:
    - version: 1.1.2

net-ssh-multi:
  gem.installed:
    - version: 1.2.0
