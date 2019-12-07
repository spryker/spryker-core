<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\StrategyResolver;

use Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface TaxRateCalculatorStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function resolve(iterable $itemTransfers): CalculatorInterface;
}
