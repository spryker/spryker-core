<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Tax\Business\Model\CalculatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ProductItemTaxRateCalculatorStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Tax\Business\Model\CalculatorInterface
     */
    public function resolve(QuoteTransfer $quoteTransfer): CalculatorInterface;
}
