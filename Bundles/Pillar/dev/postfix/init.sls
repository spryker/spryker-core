# Postfix configuration. We should use a reliable mail relay (with SPF / DKIM)
# on production system.
#
# On dev - we redirect everything to mailcatcher, which runs on port 1025
postfix:
  relay:
    host: "127.0.0.1:1025"
    user:
    api_key:
