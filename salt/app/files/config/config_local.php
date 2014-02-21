<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Solr - search engine */
$config['storage']['solr']['defaultEndpointSetup'] = [
    'host' => '{{ environment_details.solr.hostname }}',
    'port' => {{ environment_details.tomcat.port }},
];
/** FIXME */
$config['storage']['solr']['masterEndpointSetup'] = [
    'host' => '{{ environment_details.solr.hostname }}',
    'port' => {{ environment_details.tomcat.port }},
];
$config['storage']['solr']['data_dir'] = '/data/shop/{{ environment }}/shared/data/common/solr';

/** Jenkins - job manager */
/** FIXME */
$config['jenkins'] = array(
    'base_url' => 'http://fixme__jobs_hostname:{{ environment_details.tomcat.port }}/jenkins',
    'notify_email' => '',
);

/** ActiveMQ - message queue */
/** FIXME */
$config['activemq'] = array (
  array('host' => 'fixme__queue_hostname', 'port' => '4')
);

/** Amazon AWS api keys */
$config['productImage']['amazonS3Key'] = 'AKIAIFH6VVOUVCIUSAVA';
$config['productImage']['amazonS3Secret'] = '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';
$config['productImage']['amazonS3BucketName'] = 'pyz-production-upload';
