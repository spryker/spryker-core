<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis;

interface StorageRedisWrapperFactoryInterface
{
    /**
     * @return \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    public function createStorageRedisWrapper(): StorageRedisWrapperInterface;
}
