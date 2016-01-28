<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductOptionGrossSumCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);

            $productOptionUnitTotal = 0;
            $productOptionSumTotal = 0;
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionRequirements($productOptionTransfer);
                $productOptionUnitTotal += $productOptionTransfer->getUnitGrossPrice();
                $productOptionTransfer->setSumGrossPrice(
                    $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
                );
                $productOptionSumTotal += $productOptionTransfer->getSumGrossPrice();
            }

            $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice() + $productOptionUnitTotal);
            $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice() + $productOptionSumTotal);
        }
    }

    /**
     * @param ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function assertProductOptionRequirements(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->requireQuantity();
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireSumGrossPrice()->requireQuantity();
    }

}
