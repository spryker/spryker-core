<?php

/**
 * !!! This file is maintained by salt. Do not modify this file, as the changes will be overwritten!
 */
use ProjectA\Shared\System\SystemConfig;
use ProjectA\Shared\Yves\YvesConfig;

/* Session storage */
$config[SystemConfig::ZED_SESSION_SAVE_HANDLER]
    = $config[YvesConfig::YVES_SESSION_SAVE_HANDLER]
    = 'redis';
$config[SystemConfig::YVES_STORAGE_SESSION_REDIS_PROTOCOL] = 'tcp';
$config[SystemConfig::YVES_STORAGE_SESSION_REDIS_HOST] = '{{ settings.host.redis }}';
$config[SystemConfig::YVES_STORAGE_SESSION_REDIS_PORT] = '{{ settings.environments[environment].redis.port }}';

$config[SystemConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL] = $config[SystemConfig::YVES_STORAGE_SESSION_REDIS_PROTOCOL];
$config[SystemConfig::ZED_STORAGE_SESSION_REDIS_HOST] = $config[SystemConfig::YVES_STORAGE_SESSION_REDIS_HOST];
$config[SystemConfig::ZED_STORAGE_SESSION_REDIS_PORT] = $config[SystemConfig::YVES_STORAGE_SESSION_REDIS_PORT];
