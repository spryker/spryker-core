# Ruby 1.9 + gems required for deployment

install system ruby:
  pkg.installed:
    - pkgs:
      - ruby1.9.1
      - ruby-dev
      - rails
      - libncurses5-dev

psych:
  gem.installed

highline:
  gem.installed:
    - required:
      - gem: psych

compass:
  gem.installed


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
