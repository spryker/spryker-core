ufw:
  pkg.installed

ufw enable:
  cmd.run:
    - unless: "ufw status | grep 'Status: active'"

ufw default deny:
  cmd.run:
    - name: "ufw default deny"

ufw allow from 127.0.0.1:
  cmd.run:
    - unless: "ufw status| grep '127.0.0.1'"

ufw allow 443/tcp:
  cmd.run:
    - unless: "ufw status| grep '443/tcp'"

ufw allow 80/tcp:
  cmd.run:
    - unless: "ufw status| grep '80/tcp'"

ufw allow proto tcp from any to any port 22:
  cmd.run:
    - unless: "ufw status| grep '22/tcp'"

force enable:
  cmd.run:
    - name: "ufw --force enable"