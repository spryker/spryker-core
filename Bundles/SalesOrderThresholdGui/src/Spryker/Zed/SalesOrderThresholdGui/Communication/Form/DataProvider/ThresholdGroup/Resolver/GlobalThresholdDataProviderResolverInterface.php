<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface;

interface GlobalThresholdDataProviderResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): ThresholdStrategyGroupDataProviderInterface;

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyGroup(string $salesOrderThresholdTypeGroup): bool;
}
