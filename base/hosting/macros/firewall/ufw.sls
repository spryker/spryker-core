{% macro ufw_rule(rule, grep_pattern) -%}
ufw {{ rule }}:
  cmd.run:
    - unless: "ufw status | grep {{ grep_pattern }}"
{%- endmacro %}