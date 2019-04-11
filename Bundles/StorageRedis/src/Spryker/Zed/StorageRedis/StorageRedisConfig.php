<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis;

use Spryker\Shared\StorageRedis\StorageRedisConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class StorageRedisConfig extends AbstractBundleConfig
{
    /**
     * @return int|null
     */
    public function getRedisPort(): ?int
    {
        return $this->get(StorageRedisConstants::STORAGE_REDIS_PORT);
    }

    /**
     * Returns the path where the rdb dump file should be copied to.
     *
     * @return string
     */
    public function getRdbDumpPath(): string
    {
        return '/var/lib/redis/dump.rdb';
    }
}
