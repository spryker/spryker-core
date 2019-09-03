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
    protected const DEFAULT_PAGE_LENGTH = 100;

    /**
     * @deprecated Use `Spryker\Zed\StorageRedis\StorageRedisConfig::DEFAULT_REDIS_DATABASE` instead.
     */
    public const DEFAULT_REDIS_DATABASE = 0;

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
     * @return int
     */
    public function getGuiDefaultPageLength(): int
    {
        return static::DEFAULT_PAGE_LENGTH;
    }

    /**
     * @deprecated Use `Spryker\Zed\Redis\RedisConfig::getProcessTimeout()` instead.
     *
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return int, float or null to disable timeout.
     *
     * @return int|float|null
     */
    public function getProcessTimeout()
    {
        return static::PROCESS_TIMEOUT;
    }
}
