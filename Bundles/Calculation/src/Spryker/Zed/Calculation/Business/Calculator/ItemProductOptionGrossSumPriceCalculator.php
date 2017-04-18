<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemProductOptionGrossSumPriceCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionPriceCalculationRequirements($productOptionTransfer);
                $productOptionTransfer->setSumGrossPrice($productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity());
            }
        }
    }

    /**
     * @param ProductOptionTransfer $productOptionTransfer
     */
    protected function assertProductOptionPriceCalculationRequirements(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->requireQuantity();
    }
}
