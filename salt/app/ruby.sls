install system ruby:
  pkg.installed:
    - pkgs:
      - ruby1.9.1
      - ruby-dev
      - rails
      - libncurses5-dev

# Ruby gems required for deployment:
highline:
  gem.installed

net-scp:
  gem.installed

net-ssh-multi:
  gem.installed

compass:
  gem.installed