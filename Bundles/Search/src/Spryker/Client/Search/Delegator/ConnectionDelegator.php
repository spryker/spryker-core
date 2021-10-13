<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Spryker\Client\Search\Exception\ConnectDelegatorException;
use Spryker\Client\SearchExtension\Dependency\Plugin\ConnectionCheckerAdapterPluginInterface;

class ConnectionDelegator implements ConnectionDelegatorInterface
{
    /**
     * @var array<\Spryker\Client\SearchExtension\Dependency\Plugin\ConnectionCheckerAdapterPluginInterface>
     */
    protected $connectionAdapterPlugins;

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ConnectionCheckerAdapterPluginInterface> $connectionAdapterPlugins
     */
    public function __construct(array $connectionAdapterPlugins)
    {
        $this->connectionAdapterPlugins = $connectionAdapterPlugins;
    }

    /**
     * @throws \Spryker\Client\Search\Exception\ConnectDelegatorException
     *
     * @return void
     */
    public function checkConnection()
    {
        $isConnectionAdapterExecuted = false;
        foreach ($this->connectionAdapterPlugins as $connectAdapterPlugin) {
            if ($connectAdapterPlugin instanceof ConnectionCheckerAdapterPluginInterface) {
                $connectAdapterPlugin->checkConnection();
                $isConnectionAdapterExecuted = true;
            }
        }

        if (!$isConnectionAdapterExecuted) {
            throw new ConnectDelegatorException('No registered adapters with ConnectionCheckerAdapterPluginInterface.');
        }
    }
}
