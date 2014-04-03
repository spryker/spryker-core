couchbase:
  user: Administrator
  password: Wn0Ow6vHhKW8RUut
  host: 127.0.0.1
  port: 8091
  ramsize: 1500              # Max 1600MB on 2GB instance, 3200MB on 4GB instance
  data_path: /data/couchbase
  buckets:
    yves:
      bucket_size: 100
      bucket_replica: 1
    sessions:
      bucket_size: 100
      bucket_replica: 1
