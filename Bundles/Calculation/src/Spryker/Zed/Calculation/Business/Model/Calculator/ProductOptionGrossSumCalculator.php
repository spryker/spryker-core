<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

            $this->setProductOptionSumGrossPrice($itemTransfer);

            $productOptionUnitTotal = $this->getProductOptionUnitTotal($itemTransfer);
            $productOptionSumTotal = $this->getProductOptionSumTotal($itemTransfer);

            $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice() + $productOptionUnitTotal);
            $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice() + $productOptionSumTotal);

            $itemTransfer->setUnitItemTotal($itemTransfer->getUnitGrossPriceWithProductOptions());
            $itemTransfer->setSumItemTotal($itemTransfer->getSumGrossPriceWithProductOptions());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function assertProductOptionRequirements(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->requireQuantity();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionUnitTotal(ItemTransfer $itemTransfer)
    {
        $productOptionUnitTotal = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->assertProductOptionRequirements($productOptionTransfer);
            $productOptionUnitTotal += $productOptionTransfer->getUnitGrossPrice();
        }

        return $productOptionUnitTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionSumTotal(ItemTransfer $itemTransfer)
    {
        $productOptionSumTotal = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->assertProductOptionRequirements($productOptionTransfer);
            $productOptionSumTotal += $productOptionTransfer->getSumGrossPrice();
        }

        return $productOptionSumTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setProductOptionSumGrossPrice(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->assertProductOptionRequirements($productOptionTransfer);
            $productOptionTransfer->setSumGrossPrice(
                $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
            );
        }
    }

}
