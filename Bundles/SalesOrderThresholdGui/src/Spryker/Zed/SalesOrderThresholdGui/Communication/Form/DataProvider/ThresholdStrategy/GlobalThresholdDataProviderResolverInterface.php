<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

interface GlobalThresholdDataProviderResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyKey(string $salesOrderThresholdTypeKey): bool;
}
