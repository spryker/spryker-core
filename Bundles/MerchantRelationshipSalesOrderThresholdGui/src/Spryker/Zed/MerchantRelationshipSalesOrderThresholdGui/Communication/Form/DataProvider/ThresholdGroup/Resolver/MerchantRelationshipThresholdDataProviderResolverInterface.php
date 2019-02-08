<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface;

interface MerchantRelationshipThresholdDataProviderResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface
     */
    public function resolveMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): ThresholdStrategyGroupDataProviderInterface;

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): bool;
}
