<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StorageRedis;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface StorageRedisConstants
{
    /**
     * Specification:
     * - Defines a protocol for Redis connection.
     *
     * @api
     */
    public const STORAGE_REDIS_PROTOCOL = 'STORAGE_REDIS:STORAGE_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a host for Redis connection.
     *
     * @api
     */
    public const STORAGE_REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection.
     *
     * @api
     */
    public const STORAGE_REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * Specification:
     * - Defines a Redis database to connect to.
     *
     * @api
     */
    public const STORAGE_REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a password for connecting to Redis.
     *
     * @api
     */
    public const STORAGE_REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * Specification:
     * - Specifies an array of DSN strings for a multi-instance cluster/replication Redis setup.
     *
     * @api
     */
    public const STORAGE_REDIS_DATA_SOURCE_NAMES = 'STORAGE_REDIS:STORAGE_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Specifies an array of connection options.
     *
     * @api
     */
    public const STORAGE_REDIS_CONNECTION_OPTIONS = 'STORAGE_REDIS:STORAGE_REDIS_CONNECTION_OPTIONS';

    /**
     * Specification:
     * - Enables/disables debug mode for a Redis connection.
     * - Enabling debug mode will enable access statistics for a Redis connection.
     *
     * @api
     */
    public const STORAGE_REDIS_DEBUG_MODE = 'STORAGE_REDIS:STORAGE_REDIS_DEBUG_MODE';

    /**
     * Specification:
     * - Enables/disables data persistence for a Redis connection.
     *
     * @api
     */
    public const STORAGE_REDIS_PERSISTENT_CONNECTION = 'STORAGE_REDIS:STORAGE_REDIS_PERSISTENT_CONNECTION';

    /**
     * Specification:
     * - Specifies the path to rdb dump to import data from.
     *
     * @api
     */
    public const RDB_DUMP_PATH = 'STORAGE_REDIS:RDB_DUMP_PATH';
}
