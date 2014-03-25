dwh:
  postgresql:
    timezone: Europe/Berlin
    shared_buffers: 500GB
    temp_buffers: 200MB
    work_mem: 200MB
    maintenance_work_mem: 128MB
    effective_cache_size: 500GB
  cubes:
    locale: de_DE
    currency: EUR