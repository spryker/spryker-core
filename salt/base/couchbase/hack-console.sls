hack-maximum-docsize:
  cmd.run:
    - name: |
        sed 's/docBytesLimit: .*,/docBytesLimit: 2500000,/' /opt/couchbase/lib/ns_server/erlang/lib/ns_server/priv/public/js/documents.js > /tmp/documents.js &&
        mv /tmp/documents.js /opt/couchbase/lib/ns_server/erlang/lib/ns_server/priv/public/js

hack-console-title:
  cmd.run:
    - name: |
        sed 's/<title>.*<\/title>/<title>Couchbase Console ({{ grains.nodename }})<\/title>/gi' /opt/couchbase/lib/ns_server/erlang/lib/ns_server/priv/public/index.html > /tmp/index.html &&
        mv /tmp/index.html /opt/couchbase/lib/ns_server/erlang/lib/ns_server/priv/public/index.html
