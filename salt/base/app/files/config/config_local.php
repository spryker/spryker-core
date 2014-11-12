<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Jenkins - job manager */
$config['jenkins'] = array(
    'base_url' => 'http://{{ settings.host.cron_master }}:1{{ settings.environments[environment].tomcat.port_suffix }}/jenkins',
    'notify_email' => '',
);

/**
 * Cloud specific setup - in this case Rackspace only
 */
$config['cloud']['enabled'] = {{ settings.environments[environment].cloud.enabled }};
$config['cloud']['objectStorage']['enabled'] = {{ settings.environments[environment].cloud.object_storage.enabled }};

$config['cloud']['objectStorage']['rackspace']['username'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_username }}';
$config['cloud']['objectStorage']['rackspace']['apiKey'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_key }}';

$config['cloud']['cdn']['enabled'] = {{ settings.environments[environment].cloud.cdn.enabled }};

$config['lumberjack']['elasticsearch']['host'] = '{{ settings.hosts.elasticsearch_logs|first }}';

//$config['dwh'] = array(
//    'mysql-binary' => '/usr/bin/mysql',
//    'mysql-dbs' => array(
//        'zed' => $config['db'],
//    ),
//
//    'postgresql-binary' => '/usr/bin/psql',
//    'postgresql-username' => 'de_production_dwh', // store
//// FIXME:    'postgresql-password' => '', pgsql doesnt support non-interactive password
//    'postgresql-database' => 'de_production_dwh', // store
//    'postgresql-host' => '10.15.8.3', //dwh.project-yz.com
//
//    'data-dir'   => '/data/storage/production/static/DE/protected/dwh/data', // local
//    'work-dir'   => '/data/storage/production/static/DE/protected/dwh/work', // loka
//    'export-dir' => '/data/storage/production/static/DE/protected/dwh/export',
//
//    'cubes-url' => 'https://dwh-de.migusta.de', // dwh.project-yz.com extern
//
//    'decimal-mark-for-numbers' => ",",
//);
