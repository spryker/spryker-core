<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper;

interface GlobalThresholdMapperResolverInterface
{
    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function resolveGlobalThresholdMapperByStrategyKey(string $salesOrderThresholdTypeKey): GlobalThresholdFormMapperInterface;

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return bool
     */
    public function hasGlobalThresholdMapperByStrategyKey(string $salesOrderThresholdTypeKey): bool;
}
