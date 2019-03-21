<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Redis\Connection\ConnectionProvider;
use Spryker\Client\Redis\Connection\ConnectionProviderInterface;

class RedisFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Redis\Connection\ConnectionProviderInterface
     */
    public function createConnectionProvider(): ConnectionProviderInterface
    {
        return new ConnectionProvider();
    }
}
