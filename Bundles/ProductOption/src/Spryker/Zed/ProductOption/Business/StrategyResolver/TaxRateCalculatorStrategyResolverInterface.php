<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\StrategyResolver;

use Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface TaxRateCalculatorStrategyResolverInterface
{
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function resolve(): CalculatorInterface;
}
