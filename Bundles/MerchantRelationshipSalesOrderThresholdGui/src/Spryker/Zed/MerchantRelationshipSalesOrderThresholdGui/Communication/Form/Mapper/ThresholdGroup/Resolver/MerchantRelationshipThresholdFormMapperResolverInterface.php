<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipThresholdFormMapperInterface;

interface MerchantRelationshipThresholdFormMapperResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipThresholdFormMapperInterface
     */
    public function resolveMerchantRelationshipThresholdFormMapperClassInstanceByStrategyGroup(string $salesOrderThresholdTypeGroup): MerchantRelationshipThresholdFormMapperInterface;

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasMerchantRelationshipThresholdFormMapperByStrategyGroup(string $salesOrderThresholdTypeGroup): bool;
}
