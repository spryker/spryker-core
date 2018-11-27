<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper;

interface ThresholdMapperResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdFormMapperInterface
     */
    public function resolveThresholdMapperByStrategyKey(string $salesOrderThresholdTypeKey): ThresholdFormMapperInterface;

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasThresholdMapperByStrategyKey(string $salesOrderThresholdTypeKey): bool;
}
