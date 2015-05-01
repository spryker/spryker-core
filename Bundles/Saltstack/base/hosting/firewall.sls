ufw:
  pkg.installed

{% from 'hosting/macros/firewall/ufw.sls' import ufw_rule with context %}

ufw enable:
  cmd.run:
    - unless: "ufw status| grep 'Status: active'"

ufw default deny:
  cmd.run:
    - name: "ufw default deny"
# firewall rules
{{ ufw_rule('allow proto tcp from any to any port 2200', '2200/tcp')}}
{{ ufw_rule('allow 4505/tcp', '4505/tcp') }}
{{ ufw_rule('allow 4506/tcp', '4506/tcp') }}
{{ ufw_rule('allow from 127.0.0.1', '127.0.0.1') }}
{{ ufw_rule('allow 443/tcp', '443/tcp') }}
{{ ufw_rule('allow 80/tcp', '80/tcp') }}
{{ ufw_rule('allow proto tcp from any to any port 22', '22/tcp')}}

force --force enable:
  cmd.run:
    - name: "ufw --force enable"