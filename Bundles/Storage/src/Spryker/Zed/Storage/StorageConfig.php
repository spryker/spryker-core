<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage;

use Spryker\Shared\Storage\StorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class StorageConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Use `Spryker\Zed\StorageRedis\StorageRedisConfig::DEFAULT_REDIS_DATABASE` instead.
     */
    public const DEFAULT_REDIS_DATABASE = 0;
    public const DEFAULT_PROCESS_TIMEOUT = 60;

    protected const PROCESS_TIMEOUT = 60;

    /**
     * @deprecated Use `Spryker\Zed\StorageRedis\StorageRedisConfig::getRedisPort()` instead.
     *
     * @return int
     */
    public function getRedisPort()
    {
        return $this->get(StorageConstants::STORAGE_REDIS_PORT);
    }

    /**
     * @deprecated Use `Spryker\Zed\StorageRedis\StorageRedisConfig::getRdbDumpPath()` instead.
     *
     * Specification:
     * - Returns the path where the rdb dump file should be copied to.
     *
     * @return string
     */
    public function getRdbDumpPath()
    {
        return '/var/lib/redis/dump.rdb';
    }

    /**
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return 0, 0.0 or null to disable timeout.
     *
     * @return int|float|null
     */
    public function getProcessTimeout()
    {
        return static::PROCESS_TIMEOUT;
    }
}
