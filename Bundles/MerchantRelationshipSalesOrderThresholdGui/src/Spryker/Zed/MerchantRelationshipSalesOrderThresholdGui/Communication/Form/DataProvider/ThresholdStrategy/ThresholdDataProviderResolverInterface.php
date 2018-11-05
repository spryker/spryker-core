<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

interface ThresholdDataProviderResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): bool;
}
