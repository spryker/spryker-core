include:
  - .install
  - .nodejs
  - .ruby
  - .deploy
  - .environments
  - .stores
{% if 'web' in grains.roles %}
  - .nginx
  - .htpasswd
{% endif %}