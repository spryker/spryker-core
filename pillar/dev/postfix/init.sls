# redirect everything to mailcatcher, which runs on port 1025
postfix:
  relay:
    host: "127.0.0.1:1025"
    user: 
    api_key: 