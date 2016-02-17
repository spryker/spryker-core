<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

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
            $item->setCalculatedDiscounts(new \ArrayObject());

            foreach ($item->getProductOptions() as $option) {
                $option->setCalculatedDiscounts(new \ArrayObject());
            }
        }

        foreach ($quoteTransfer->getExpenses() as $expense) {
            $expense->setCalculatedDiscounts(new \ArrayObject());
        }
    }

}
