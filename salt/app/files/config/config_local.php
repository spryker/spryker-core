{%- set netif = pillar.network.project_interface %}
{%- set solr_master = salt['mine.get']('solr_role:master', 'network.interfaces', expr_form = 'grain').items()[0][1].network_settings[netif]['inet'][0].address %}
{%- set cron_master = salt['mine.get']('roles:cronjobs', 'network.interfaces', expr_form = 'grain').items()[0][1].network_settings[netif]['inet'][0].address %}
{%- set queue_host = salt['mine.get']('roles:queue', 'network.interfaces', expr_form = 'grain').items()[0][1].network_settings[netif]['inet'][0].address %}
<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Solr - search engine */
/** default - used for queries */
// Fixme - use cloud loadbalancer instead of single server
$config['storage']['solr']['defaultEndpointSetup'] = [
//    'host' => '{{ environment_details.solr.lb_hostname }}',
    'host' => '{{ solr_master }}',
    'port' => 1{{ environment_details.tomcat.port_suffix }},
];
/** master - used for updates */
$config['storage']['solr']['masterEndpointSetup'] = [
    'host' => '{{ solr_master }}',
    'port' => 1{{ environment_details.tomcat.port_suffix }},
];
$config['storage']['solr']['data_dir'] = '/data/shop/{{ environment }}/shared/data/common/solr';

/** Jenkins - job manager */
$config['jenkins'] = array(
    'base_url' => 'http://{{ cron_master }}:1{{ environment_details.tomcat.port_suffix }}/jenkins',
    'notify_email' => '',
);

/** ActiveMQ - message queue */
/** FIXME */
$config['activemq'] = array (
  array('host' => '{{ queue_host }}', 'port' => '{{ environment_details.queue.stomp_port }}')
);

/** Amazon AWS api keys */
$config['productImage']['amazonS3Key'] = 'AKIAIFH6VVOUVCIUSAVA';
$config['productImage']['amazonS3Secret'] = '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';
$config['productImage']['amazonS3BucketName'] = 'pyz-production-upload';
