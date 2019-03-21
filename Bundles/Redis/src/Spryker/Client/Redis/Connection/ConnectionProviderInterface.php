<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Connection;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;

interface ConnectionProviderInterface
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
     * @return \Predis\Client
     */
    public function getConnection(string $connectionKey): Client;
}
