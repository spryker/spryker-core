<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 *
 */

/** Database: ZED operations
 *
 * The same username / password / database is used both for MySQL (old) and PostgreSQL (new)
 */
$config['db'] = [
    'username' => '{{ settings.environments[environment].stores[store].zed.database.username }}',
    'password' => '{{ settings.environments[environment].stores[store].zed.database.password }}',
    'database' => '{{ settings.environments[environment].stores[store].zed.database.database }}',
    'host'     => '{{ settings.environments[environment].stores[store].zed.database.hostname }}',
];

/** Database: ZED dump
 *
 * The same username / password / database is used both for MySQL (old) and PostgreSQL (new)
 */
$config['db_dump'] = [
    'username' => '{{ settings.environments[environment].stores[store].dump.database.username }}',
    'password' => '{{ settings.environments[environment].stores[store].dump.database.password }}',
    'database' => '{{ settings.environments[environment].stores[store].dump.database.database }}',
    'host'     => '{{ settings.environments[environment].stores[store].dump.database.hostname }}',
    'mysqldump_bin' => '/usr/bin/mysqldump',
    'mysql_bin'     => '/usr/bin/mysql',
];

/** Public URL's */
$config['host'] = $config['host_ssl'] = [
    'zed_gui' => '{{ settings.environments[environment].stores[store].zed.hostname }}',
    'zed_api' => 'localhost:{{ settings.environments[environment].stores[store].zed.port }}',
    'yves' => '{{ settings.environments[environment].stores[store].yves.hostnames[0] }}',
];

/** Session storage */
$config['zed']['session']['save_handler'] = 'redis';
$config['zed']['session']['save_path'] = 'tcp://{{ settings.host.redis }}:{{ settings.environments[environment].redis.port }}?database=1';
$config['yves']['session'] = $config['zed']['session'];

/** Key-Value storage */
$config['storage']['kv']['source'] = 'redis';
$config['storage']['kv']['redis']['protocol'] = 'tcp';
$config['storage']['kv']['redis']['host'] = '{{ settings.environments[environment].redis.host }}';
$config['storage']['kv']['redis']['port'] = '{{ settings.environments[environment].redis.port }}';

/** Cloud-based Object Storage */
$config['cloud']['cdn']['static_media']['http'] = '{{ settings.environments[environment].cloud.cdn.static_media[store].http }}';
$config['cloud']['cdn']['static_media']['https'] = '{{ settings.environments[environment].cloud.cdn.static_media[store].https }}';

$config['cloud']['cdn']['static_assets']['http'] = '{{ settings.environments[environment].cloud.cdn.static_assets[store].http }}';
$config['cloud']['cdn']['static_assets']['https'] = '{{ settings.environments[environment].cloud.cdn.static_assets[store].https }}';
