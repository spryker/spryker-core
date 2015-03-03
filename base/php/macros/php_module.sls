#
# Macro: Enable or disable PHP module
#

{% macro php_module(name, enable, sapi) -%}
{% if enable %}
enable-php-module-{{ name }}-for-{{ sapi }}:
  cmd.run:
    - name: php5enmod -s {{ sapi }} {{ name }}
    - unless: php5query -s {{ sapi }} -m {{ name }}
    - require:
      - file: /etc/php5/mods-available/{{ name }}.ini
{% else %}
disable-php-module-{{ name }}-for-{{ sapi }}:
  cmd.run:
    - name: php5dismod -s {{ sapi }} {{ name }}
    - onlyif: php5query -s {{ sapi }} -m {{ name }}
{% endif %}

{% endmacro %}
