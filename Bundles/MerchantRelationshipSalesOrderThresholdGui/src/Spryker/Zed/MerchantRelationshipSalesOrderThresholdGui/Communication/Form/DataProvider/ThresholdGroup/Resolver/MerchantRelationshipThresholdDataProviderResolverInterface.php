<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyDataProviderInterface;

interface MerchantRelationshipThresholdDataProviderResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyDataProviderInterface
     */
    public function resolveMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasMerchantRelationshipThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): bool;
}
