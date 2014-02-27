{%- set netif = pillar.network.project_interface -%}
{%- set solr_master = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address -%}
{%- set cron_master = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address -%}
{%- set queue_host = salt['mine.get']('roles:queue', 'network.interfaces', expr_form = 'grain').items()[0][1][netif]['inet'][0].address -%}
{%- set couchbase_servers = salt['mine.get']('roles:couchbase', 'network.interfaces', expr_form = 'grain').items() -%}
<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Solr - default - used for queries */
$config['storage']['solr']['defaultEndpointSetup'] = [
    'host' => '{{ environment_details.solr.lb_hostname }}',
    'port' => 1{{ environment_details.tomcat.port_suffix }},
];

/** Solr - master - used for updates */
$config['storage']['solr']['masterEndpointSetup'] = [
    'host' => '{{ solr_master }}',
    'port' => 1{{ environment_details.tomcat.port_suffix }},
];

/** Solr - local - used for setup */
$config['storage']['solr']['localEndpointSetup'] = [
    'host' => 'localhost',
    'port' => 1{{ environment_details.tomcat.port_suffix }},
];
$config['storage']['solr']['data_dir'] = '/data/shop/{{ environment }}/shared/data/common/solr';

/** Jenkins - job manager */
$config['jenkins'] = array(
    'base_url' => 'http://{{ cron_master }}:1{{ environment_details.tomcat.port_suffix }}/jenkins',
    'notify_email' => '',
);

/** ActiveMQ - message queue */
$config['activemq'] = array (
  array('host' => '{{ queue_host }}', 'port' => '{{ environment_details.queue.stomp_port }}')
);

/** Session storage */
$config['zed']['session']['save_handler'] = 'couchbase';
$config['zed']['session']['save_path'] = '{%- for hostname, network_settings in couchbase_servers -%}
{{ network_settings[netif]['inet'][0]['address'] }}:{{ pillar.couchbase.port }}{% if not loop.last %};{% endif -%}{% endfor %}'

$config['yves']['session'] = $config['zed']['session'];

/** Amazon AWS api keys - not used for rackspace projects */
// $config['productImage']['amazonS3Key'] = 'AKIAIFH6VVOUVCIUSAVA';
// $config['productImage']['amazonS3Secret'] = '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';
// $config['productImage']['amazonS3BucketName'] = 'pyz-production-upload';
