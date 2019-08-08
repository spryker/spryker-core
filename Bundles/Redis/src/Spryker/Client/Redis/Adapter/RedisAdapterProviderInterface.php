<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Generated\Shared\Transfer\RedisConfigurationTransfer;

interface RedisAdapterProviderInterface
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
     * @throws \Spryker\Client\Redis\Exception\RedisAdapterNotInitializedException
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    public function getAdapter(string $connectionKey): RedisAdapterInterface;
}
