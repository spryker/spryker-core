<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 *
 */

/** Database: ZED operations */
$config['db'] = [
    'username' => '{{ settings.environments[environment].stores[store].zed.database.username }}',
    'password' => '{{ settings.environments[environment].stores[store].zed.database.password }}',
    'database' => '{{ settings.environments[environment].stores[store].zed.database.database }}',
    'host'     => '{{ settings.environments[environment].stores[store].zed.database.hostname }}',
];

/** Database: ZED dump */
$config['db_dump'] = [
    'username' => '{{ settings.environments[environment].stores[store].zed.database.username }}',
    'password' => '{{ settings.environments[environment].stores[store].zed.database.password }}',
    'database' => '{{ settings.environments[environment].stores[store].zed.database.database }}',
    'host'     => '{{ settings.environments[environment].stores[store].zed.database.hostname }}',
    'mysqldump_bin' => '/usr/bin/mysqldump',
    'mysql_bin'     => '/usr/bin/mysql',
];

/** Mysql as KeyValue store */
$config['storage']['kv'] = [
    'source' => 'mysql',
    'mysql' => [
        'host' => '{{ settings.environments[environment].database.shared_data.hostname }}',
        'user' => '{{ settings.environments[environment].database.shared_data.username }}',
        'password' => '{{ settings.environments[environment].database.shared_data.password }}',
        'database' => '{{ settings.environments[environment].database.shared_data.database }}',
        'port' => '{{ settings.environments[environment].database.shared_data.port }}',
        'table' => '{{ settings.environments[environment].database.shared_data.table }}',
    ]
];

/** Public URL's */
$config['host'] = $config['host_ssl'] = [
    'zed_gui' => '{{ settings.environments[environment].stores[store].zed.hostname }}',
    'zed_api' => 'localhost:{{ settings.environments[environment].stores[store].zed.port }}', 
    'yves' => '{{ settings.environments[environment].stores[store].yves.hostnames[0] }}',
];

/** Session storage */
$config['zed']['session']['save_handler'] = 'couchbase';
$config['zed']['session']['save_path'] = '{%- for host in settings.hosts.couchbase -%}
{{store}}_{{environment}}_sessions:{{ pillar.couchbase.password }}@{{ host }}:{{ pillar.couchbase.port }}{% if not loop.last %};{% endif -%}{% endfor %}';

$config['yves']['session'] = $config['zed']['session'];

$config['cloud']['cdn']['static_media']['http'] = '{{ settings.environments[environment].cloud.cdn.static_media[store].http }}';
$config['cloud']['cdn']['static_media']['https'] = '{{ settings.environments[environment].cloud.cdn.static_media[store].https }}';

$config['cloud']['cdn']['static_assets']['http'] = '{{ settings.environments[environment].cloud.cdn.static_assets[store].http }}';
$config['cloud']['cdn']['static_assets']['https'] = '{{ settings.environments[environment].cloud.cdn.static_assets[store].https }}';

// Fixme
/** Payment gateways */
/** Logistic partners */
/** MCM, tracking */
/** Facebook / external auth providers */
