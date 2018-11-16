<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface StorageConstants
{
    public const STORAGE_KV_SOURCE = 'STORAGE_KV_SOURCE';
    public const STORAGE_REDIS_PROTOCOL = 'YVES_STORAGE_REDIS_PROTOCOL';
    public const STORAGE_REDIS_PASSWORD = 'YVES_STORAGE_REDIS_PASSWORD';
    public const STORAGE_REDIS_HOST = 'YVES_STORAGE_REDIS_HOST';
    public const STORAGE_REDIS_PORT = 'YVES_STORAGE_REDIS_PORT';
    public const STORAGE_PERSISTENT_CONNECTION = 'YVES_STORAGE_PERSISTENT_CONNECTION';
    public const STORAGE_REDIS_DATABASE = 'YVES_STORAGE_REDIS_DATABASE';

    public const STORAGE_CACHE_STRATEGY = 'STORAGE_CACHE_STRATEGY';
    public const STORAGE_CACHE_STRATEGY_REPLACE = 'STORAGE_CACHE_STRATEGY_REPLACE';
    public const STORAGE_CACHE_STRATEGY_INCREMENTAL = 'STORAGE_CACHE_STRATEGY_INCREMENTAL';
    public const STORAGE_CACHE_STRATEGY_INACTIVE = 'STORAGE_CACHE_STRATEGY_INACTIVE';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     * @see https://github.com/nrk/predis/wiki/Connection-Parameters for details.
     *
     * @api
     */
    public const STORAGE_PREDIS_CLIENT_CONFIGURATION = 'STORAGE_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client.
     * - @see https://github.com/nrk/predis/wiki/Client-Options for details.
     *
     * @api
     */
    public const STORAGE_PREDIS_CLIENT_OPTIONS = 'STORAGE_PREDIS_CLIENT_OPTIONS';
}
