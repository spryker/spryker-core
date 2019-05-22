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
    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @return int|null
     */
    public function getRedisPort(): ?int
    {
        return $this->get(StorageRedisConstants::STORAGE_REDIS_PORT);
    }

    /**
     * @return string
     */
    public function getRdbDumpPath(): string
    {
        return $this->get(StorageRedisConstants::RDB_DUMP_PATH);
    }
}
