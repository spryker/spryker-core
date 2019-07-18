<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;

class MerchantRelationshipThresholdDataProviderResolver implements MerchantRelationshipThresholdDataProviderResolverInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig $config
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(MerchantRelationshipSalesOrderThresholdGuiConfig $config, array $formExpanderPlugins)
    {
        $this->config = $config;
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdDataProviderException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface
     */
    public function resolveMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): ThresholdStrategyGroupDataProviderInterface
    {
        if (!$this->hasMerchantRelationshipThresholdDataProviderByStrategyGroup($salesOrderThresholdTypeGroup)) {
            throw new MissingThresholdDataProviderException();
        }

        $dataProvider = $this->config->getStrategyGroupToDataProviderMap()[$salesOrderThresholdTypeGroup];

        return new $dataProvider($this->formExpanderPlugins);
    }

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): bool
    {
        return array_key_exists($salesOrderThresholdTypeGroup, $this->config->getStrategyGroupToDataProviderMap());
    }
}
