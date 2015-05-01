#
# Setup for multiple environments of Spryker
#
# This implementation is Spryker-specific and it takes data from Spryker pillars
# Instances created here are used by Spryker and are required for production use.
# Each environment works on seperate redis instance.
#
{% from 'settings/init.sls' import settings with context %}
{% from 'redis/macros/redis_instance.sls' import redis_instance with context %}

/etc/logrotate.d/redis-instances:
  file.managed:
    - source: salt://redis/files/etc/logrotate.d/redis-instances

{%- for environment, environment_details in pillar.environments.items() %}
{{ redis_instance(environment, environment_details, settings) }}
{%- endfor %}
