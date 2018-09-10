<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

interface ThresholdDataProviderResolverInterface
{
    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): bool;
}
