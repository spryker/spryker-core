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

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PROTOCOL` instead.
     */
    public const STORAGE_REDIS_PROTOCOL = 'YVES_STORAGE_REDIS_PROTOCOL';

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD` instead.
     */
    public const STORAGE_REDIS_PASSWORD = 'YVES_STORAGE_REDIS_PASSWORD';

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST` instead.
     */
    public const STORAGE_REDIS_HOST = 'YVES_STORAGE_REDIS_HOST';

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT` instead.
     */
    public const STORAGE_REDIS_PORT = 'YVES_STORAGE_REDIS_PORT';

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PERSISTENT_CONNECTION` instead.
     */
    public const STORAGE_PERSISTENT_CONNECTION = 'YVES_STORAGE_PERSISTENT_CONNECTION';

    /**
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE` instead.
     */
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
     *
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATA_SOURCE_NAMES` instead.
     */
    public const STORAGE_PREDIS_CLIENT_CONFIGURATION = 'STORAGE_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client.
     * - @see https://github.com/nrk/predis/wiki/Client-Options for details.
     *
     * @api
     *
     * @deprecated Use `Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_CONNECTION_OPTIONS` instead.
     */
    public const STORAGE_PREDIS_CLIENT_OPTIONS = 'STORAGE_PREDIS_CLIENT_OPTIONS';
}
