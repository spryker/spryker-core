<?php
/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */

/** Jenkins - (cron)job manager */
$config['jenkins'] = array(
    'base_url' => 'http://{{ settings.host.cron_master }}:{{ settings.environments[environment].jenkins.port }}',
    'notify_email' => '',
);

/**
 * Cloud-specific setup - in this case Rackspace only
 */
$config['cloud']['enabled'] = {{ settings.environments[environment].cloud.enabled }};
$config['cloud']['objectStorage']['enabled'] = {{ settings.environments[environment].cloud.object_storage.enabled }};

$config['cloud']['objectStorage']['rackspace']['username'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_username }}';
$config['cloud']['objectStorage']['rackspace']['apiKey'] = '{{ settings.environments[environment].cloud.object_storage.rackspace.api_key }}';

$config['cloud']['cdn']['enabled'] = {{ settings.environments[environment].cloud.cdn.enabled }};

// $config['lumberjack']['elasticsearch']['host'] = '{{ settings.hosts.elasticsearch_logs|first }}';
