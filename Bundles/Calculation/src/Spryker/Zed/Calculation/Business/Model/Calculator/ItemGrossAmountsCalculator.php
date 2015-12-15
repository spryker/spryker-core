<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ItemGrossAmountsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->addCalculatedItemGrossAmounts($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCalculatedItemGrossAmounts(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitGrossPrice()->requireQuantity();
        $itemTransfer->setSumGrossPrice($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());

        $this->withProductOptionGrossAmounts($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function withProductOptionGrossAmounts(ItemTransfer $itemTransfer)
    {
        $totalProductOptionGrossSum = 0;
        $totalProductOptionGrossUnit = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $totalProductOptionGrossSum += $productOptionTransfer->getSumGrossPrice();
            $totalProductOptionGrossUnit += $productOptionTransfer->getUnitGrossPrice();
        }

        $itemTransfer->setSumGrossPriceWithProductOptions(
            $itemTransfer->getSumGrossPrice() + $totalProductOptionGrossSum
        );

        $itemTransfer->setUnitGrossPriceWithProductOptions(
            $itemTransfer->getUnitGrossPrice() + $totalProductOptionGrossUnit
        );
    }

}
