<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class SubtotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $subtotal = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $subtotal += $itemTransfer->getSumAggregation();
        }

        $quoteTransfer->getTotals()->setSubtotal($subtotal) ;
    }
}
