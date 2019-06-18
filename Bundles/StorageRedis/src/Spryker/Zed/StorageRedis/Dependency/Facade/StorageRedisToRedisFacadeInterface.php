<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Dependency\Facade;

interface StorageRedisToRedisFacadeInterface
{
    /**
     * @param string $destination
     * @param int $redisPort
     *
     * @return bool
     */
    public function export(string $destination, int $redisPort): bool;

    /**
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    public function import(string $source, string $destination): bool;
}
