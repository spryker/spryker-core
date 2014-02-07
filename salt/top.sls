base:
  '*':
    - base
    - user

  'roles:couchbase':
    - match: grain
    - couchbase