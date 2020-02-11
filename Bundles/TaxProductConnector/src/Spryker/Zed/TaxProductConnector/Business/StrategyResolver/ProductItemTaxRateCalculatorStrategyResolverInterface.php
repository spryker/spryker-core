<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ProductItemTaxRateCalculatorStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function resolve(QuoteTransfer $quoteTransfer): CalculatorInterface;
}
