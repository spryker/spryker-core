<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Client\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface;

interface ClientAdapterFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): ClientAdapterInterface;
}
