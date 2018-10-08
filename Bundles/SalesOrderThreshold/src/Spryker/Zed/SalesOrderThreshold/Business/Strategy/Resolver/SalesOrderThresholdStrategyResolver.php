<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver;

use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface;

class SalesOrderThresholdStrategyResolver implements SalesOrderThresholdStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface[]
     */
    protected $salesOrderThresholdStrategyPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface[] $salesOrderThresholdStrategyPlugins
     */
    public function __construct(array $salesOrderThresholdStrategyPlugins)
    {
        $this->salesOrderThresholdStrategyPlugins = $salesOrderThresholdStrategyPlugins;
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface
     */
    public function resolveSalesOrderThresholdStrategy(string $salesOrderThresholdTypeKey): SalesOrderThresholdStrategyPluginInterface
    {
        foreach ($this->salesOrderThresholdStrategyPlugins as $salesOrderThresholdStrategy) {
            if ($salesOrderThresholdStrategy->getKey() === $salesOrderThresholdTypeKey) {
                return $salesOrderThresholdStrategy;
            }
        }

        throw new SalesOrderThresholdTypeNotFoundException($salesOrderThresholdTypeKey);
    }
}
