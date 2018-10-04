<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;

class GlobalThresholdDataProviderResolver implements GlobalThresholdDataProviderResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig $config
     */
    public function __construct(SalesOrderThresholdGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): ThresholdStrategyDataProviderInterface
    {
        if (!$this->hasGlobalThresholdDataProviderByStrategyKey($salesOrderThresholdTypeKey)) {
            throw new MissingThresholdDataProviderException();
        }
        /** @var \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface $dataProvider */
        $dataProvider = $this->config->getStrategyTypeToDataProviderMap()[$salesOrderThresholdTypeKey];

        return new $dataProvider();
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): bool
    {
        return array_key_exists($salesOrderThresholdTypeKey, $this->config->getStrategyTypeToDataProviderMap());
    }
}
