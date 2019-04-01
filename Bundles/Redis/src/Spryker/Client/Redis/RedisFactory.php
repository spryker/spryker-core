<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Redis\Client\ClientProvider;
use Spryker\Client\Redis\Client\ClientProviderInterface;
use Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface;
use Spryker\Client\Redis\Client\Factory\PredisClientAdapterFactory;

class RedisFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Redis\Client\ClientProviderInterface
     */
    public function createConnectionProvider(): ClientProviderInterface
    {
        return new ClientProvider(
            $this->createClientFactory()
        );
    }

    /**
     * @return \Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface
     */
    public function createClientFactory(): ClientAdapterFactoryInterface
    {
        return new PredisClientAdapterFactory();
    }
}
