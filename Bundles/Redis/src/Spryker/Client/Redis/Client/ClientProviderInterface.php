<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Client;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface;

interface ClientProviderInterface
{
    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void;

    /**
     * @param string $connectionKey
     *
     * @throws \Spryker\Client\Redis\Exception\ConnectionNotInitializedException
     *
     * @return \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface
     */
    public function getClient(string $connectionKey): ClientAdapterInterface;
}
