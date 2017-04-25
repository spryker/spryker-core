<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class RemoveAllCalculatedDiscountsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $item) {
            foreach ($item->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->setCalculatedDiscounts(new ArrayObject());
            }
            $item->setCalculatedDiscounts(new ArrayObject());
        }

        foreach ($quoteTransfer->getExpenses() as $expense) {
            $expense->setCalculatedDiscounts(new ArrayObject());
        }
    }

}
