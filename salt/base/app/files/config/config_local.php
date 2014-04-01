<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Solr - default - used for queries */
$config['storage']['solr']['defaultEndpointSetup'] = [
    'host' => '{{ settings.environments[environment].solr.lb_hostname }}',
    'port' => 1{{ settings.environments[environment].tomcat.port_suffix }},
];

/** Solr - master - used for updates */
$config['storage']['solr']['masterEndpointSetup'] = [
    'host' => '{{ settings.host.solr_master }}',
    'port' => 1{{ settings.environments[environment].tomcat.port_suffix }},
];

/** Solr - local - used for setup */
$config['storage']['solr']['localEndpointSetup'] = [
    'host' => 'localhost',
    'port' => 1{{ settings.environments[environment].tomcat.port_suffix }},
];
$config['storage']['solr']['data_dir'] = '/data/shop/{{ environment }}/shared/data/common/solr';

/** Jenkins - job manager */
$config['jenkins'] = array(
    'base_url' => 'http://{{ settings.host.cron_master }}:1{{ settings.environments[environment].tomcat.port_suffix }}/jenkins',
    'notify_email' => '',
);

/** ActiveMQ - message queue */
$config['activemq'] = array (
  array('host' => '{{ settings.host.queue }}', 'port' => '{{ settings.environments[environment].queue.stomp_port }}')
);

$config['kibana'] = [
    'base_url' => 'https://{{ pillar.kibana.hostname }}',
];
/** Amazon AWS api keys - not used for rackspace projects */
// $config['productImage']['amazonS3Key'] = 'AKIAIFH6VVOUVCIUSAVA';
// $config['productImage']['amazonS3Secret'] = '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';
// $config['productImage']['amazonS3BucketName'] = 'pyz-production-upload';


/**
 * Cloud specific setup - in this case Rackspace only
 */
$config['cloud']['enabled'] = {{ settings.environments[environment].cloud.enabled }};
$config['cloud']['objectStorage']['enabled'] = {{ settings.environments[environment].cloud.object_storage.enabled }};

$config['cloud']['objectStorage']['rackspace']['username'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_username }}';
$config['cloud']['objectStorage']['rackspace']['apiKey'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_key }}';

$config['cloud']['cdn']['enabled'] = {{ settings.environments[environment].cloud.cdn.enabled }};


$config['storage']['solr']['endpointGroups'] = [
    'stores' => [
        {%- for store, store_details in pillar.stores.items() %}
        '{{ store }}',
        {%- endfor %}
    ],
];

$config['storage']['solr']['endpoint'] = [
    {%- for store, store_details in pillar.stores.items() %}
    '{{ store }}' => [
        'core' => '{{ store }}'
    ],
    {%- endfor %}
    ],
];

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
