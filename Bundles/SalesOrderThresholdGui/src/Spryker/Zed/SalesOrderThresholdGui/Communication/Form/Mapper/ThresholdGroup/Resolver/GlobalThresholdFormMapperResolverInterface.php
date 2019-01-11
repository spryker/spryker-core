<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface;

interface GlobalThresholdFormMapperResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface
     */
    public function resolveGlobalThresholdFormMapperClassInstanceByStrategyGroup(string $salesOrderThresholdTypeGroup): GlobalThresholdFormMapperInterface;

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasGlobalThresholdFormMapperByStrategyGroup(string $salesOrderThresholdTypeGroup): bool;
}
