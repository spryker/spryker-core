<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

interface ThresholdMapperResolverInterface
{
    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface
     */
    public function resolveThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): ThresholdFormMapperInterface;

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): bool;
}
