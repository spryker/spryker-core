<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdDataProviderException;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;

class ThresholdDataProviderResolver implements ThresholdDataProviderResolverInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig $config
     */
    public function __construct(MerchantRelationshipMinimumOrderValueGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdDataProviderException
     *
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): ThresholdStrategyDataProviderInterface
    {
        if (!$this->hasThresholdDataProviderByStrategyKey($minimumOrderValueTypeKey)) {
            throw new MissingThresholdDataProviderException();
        }
        /** @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface $dataProvider */
        $dataProvider = $this->config->getStrategyTypeToDataProviderMap()[$minimumOrderValueTypeKey];

        return new $dataProvider();
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): bool
    {
        return array_key_exists($minimumOrderValueTypeKey, $this->config->getStrategyTypeToDataProviderMap());
    }
}
