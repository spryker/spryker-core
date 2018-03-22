<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage;

interface StorageConstants
{
    const STORAGE_KV_SOURCE = 'STORAGE_KV_SOURCE';
    const STORAGE_REDIS_PROTOCOL = 'YVES_STORAGE_REDIS_PROTOCOL';
    const STORAGE_REDIS_PASSWORD = 'YVES_STORAGE_REDIS_PASSWORD';
    const STORAGE_REDIS_HOST = 'YVES_STORAGE_REDIS_HOST';
    const STORAGE_REDIS_PORT = 'YVES_STORAGE_REDIS_PORT';
    const STORAGE_PERSISTENT_CONNECTION = 'YVES_STORAGE_PERSISTENT_CONNECTION';
    const STORAGE_REDIS_DATABASE = 'YVES_STORAGE_REDIS_DATABASE';

    const STORAGE_CACHE_STRATEGY = 'STORAGE_CACHE_STRATEGY';
    const STORAGE_CACHE_STRATEGY_REPLACE = 'STORAGE_CACHE_STRATEGY_REPLACE';
    const STORAGE_CACHE_STRATEGY_INCREMENTAL = 'STORAGE_CACHE_STRATEGY_INCREMENTAL';
    const STORAGE_CACHE_STRATEGY_INACTIVE = 'STORAGE_CACHE_STRATEGY_INACTIVE';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     * @see https://github.com/nrk/predis/wiki/Connection-Parameters for details.
     *
     * @api
     */
    const STORAGE_PREDIS_CLIENT_CONFIGURATION = 'STORAGE_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client.
     * - @see https://github.com/nrk/predis/wiki/Client-Options for details.
     *
     * @api
     */
    const STORAGE_PREDIS_CLIENT_OPTIONS = 'STORAGE_PREDIS_CLIENT_OPTIONS';
}
