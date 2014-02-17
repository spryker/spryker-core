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
    'host' => '{{ environment_details.database.zed.hostname }}',
];
// Fixme
/** Memcache as KeyValue store */
$config['storage']['kv'] = [
    'source' => 'memcached',
    'memcached' => [
        'host'   => 'localhost',
        'port'   => '15109',
        'prefix' => ''
    ]
];
// Fixme

/** Public URL's */
$config['host'] = $config['host_ssl'] = [
    'zed_gui' => '{{ environment_details.stores[store].zed.hostname }}',
    'zed_api' => 'localhost:fixme', 
    'yves' => '{{ environment_details.stores[store].yves.hostnames[0] }}',
    'static_assets' => '{{ environment_details.static.hostname }}',
    'static_media' => '{{ environment_details.static.hostname }}',
];

// Fixme
$config['yves']['session'] = [
    'save_handler' => 'memcached',
    'save_path' => 'projectyz-int-db1:15108,projectyz-int-db2:15108',
];

// Fixme
$config['zed']['session'] = $config['yves']['session'];

// Fixme
/** Payment gateways */
/** Logistic partners */
/** MCM, tracking */
/** Facebook / external auth providers */
