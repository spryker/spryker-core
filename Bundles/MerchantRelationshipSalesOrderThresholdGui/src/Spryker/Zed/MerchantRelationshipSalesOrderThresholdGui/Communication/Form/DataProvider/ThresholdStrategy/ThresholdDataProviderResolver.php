<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;

class ThresholdDataProviderResolver implements ThresholdDataProviderResolverInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig $config
     */
    public function __construct(MerchantRelationshipSalesOrderThresholdGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): ThresholdStrategyDataProviderInterface
    {
        if (!$this->hasThresholdDataProviderByStrategyKey($salesOrderThresholdTypeKey)) {
            throw new MissingThresholdDataProviderException();
        }
        /** @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface $dataProvider */
        $dataProvider = $this->config->getStrategyTypeToDataProviderMap()[$salesOrderThresholdTypeKey];

        return new $dataProvider();
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): bool
    {
        return array_key_exists($salesOrderThresholdTypeKey, $this->config->getStrategyTypeToDataProviderMap());
    }
}
