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
    'zed_gui' => 'zed.project-yz.com',
    'zed_api' => 'localhost:15101', 
    'yves' => 'www.project-yz.com',
    'static_assets' => 'static.project-yz.com',
    'static_media' => 'static.project-yz.com',
];

// Fixme
$config['yves']['session'] = [
    'save_handler' => 'memcached',
    'save_path' => 'projectyz-int-db1:15108,projectyz-int-db2:15108',
];

// Fixme
$config['zed']['session'] = $config['yves']['session'];

/** Payment gateways */

// Fixme
$config['payone'] = [
  'mode' => 'test',
  'mid' => '24047',
  'portalid' => '2017184',
  'key' => '3AHSu99Q7Bi2H03n',
  'aid' => '24058',
  'encoding'   => 'UTF-8',
  'gatewayurl' => 'https://api.pay1.de/post-gateway/',
];
