<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Connection\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\Redis\Connection\Adapter\RedisAdapterInterface;

interface RedisAdapterFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Connection\Adapter\RedisAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface;
}
