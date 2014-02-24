<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 *
 */

/** Database: ZED operations */
$config['db'] = [
    'username' => '{{ environment_details.database.zed.username }}',
    'password' => '{{ environment_details.database.zed.password }}',
    'database' => '{{ store }}_{{ environment}}_zed',
    'host'     => '{{ environment_details.database.zed.hostname }}',
];

$config['db_dump'] = [
    'username' => '{{ environment_details.database.zed.username }}',
    'password' => '{{ environment_details.database.zed.password }}',
    'database' => '{{ store }}_{{ environment}}_dump',
    'host'     => '{{ environment_details.database.zed.hostname }}',
    'mysqldump_bin' => '/usr/bin/mysqldump',
    'mysql_bin'     => '/usr/bin/mysql',
];

// Fixme
/** Memcache as KeyValue store */
$config['storage']['kv'] = [
    'source' => 'couchbase',
    'couchbase' => [
        'host'   => '10.16.0.31:8091',
        'user'   => 'Administrator',
        'password' => 'Wn0Ow6vHhKW8RUut',
        'bucket' => 'DE_production_yves'
    ]
];

/** Public URL's */
$config['host'] = $config['host_ssl'] = [
    'zed_gui' => '{{ environment_details.stores[store].zed.hostname }}',
    'zed_api' => 'localhost:{{ environment_details.stores[store].zed.port }}', 
    'yves' => '{{ environment_details.stores[store].yves.hostnames[0] }}',
    'static_assets' => '{{ environment_details.static.hostname }}',
    'static_media' => '{{ environment_details.static.hostname }}',
];

// Fixme
$config['yves']['session'] = [
    'save_handler' => 'files',
    'save_path' => '/tmp',
];


// Fixme
$config['zed']['session'] = $config['yves']['session'];

// Fixme
/** Payment gateways */
/** Logistic partners */
/** MCM, tracking */
/** Facebook / external auth providers */
