#
# Tools and settings for local development
#

# Pre-fetch SSH key for git repository
get-github-ssh-hostkey:
  cmd.run:
    - name: ssh-keyscan -H {{ pillar.deploy.git_hostname }} >> /home/vagrant/.ssh/known_hosts
    - unless: test -f /home/vagrant/.ssh/known_hosts
    - user: vagrant

# Install / Configure Oh-My-Zsh for user vagrant
clone-oh-my-zsh:
  cmd.run:
    - name: git clone git://github.com/robbyrussell/oh-my-zsh.git /home/vagrant/.oh-my-zsh
    - unless: test -d /home/vagrant/.oh-my-zsh
    - user: vagrant

# Create inital .zshrc, allow editing it by user (don't replace contents)
/home/vagrant/.zshrc:
  file.managed:
    - source: salt://development/files/home/vagrant/.zshrc
    - user: vagrant
    - group: vagrant
    - mode: 600
    - replace: False

/home/vagrant/.oh-my-zsh/plugins/spryker:
  file.recurse:
    - source: salt://development/files/home/vagrant/oh-my-zsh/plugins/spryker
    - user: vagrant
    - group: vagrant
    - file_mode: 600
    - dir_mode: 755


