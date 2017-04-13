<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemTaxAmountFullAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productOptionTaxAmount = $this->calculateProductOptionTaxAmount($itemTransfer);
            $itemTransfer->setTaxAmountFullAggregation($itemTransfer->getSumTaxAmount() + $productOptionTaxAmount);
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateProductOptionTaxAmount(ItemTransfer $itemTransfer)
    {
        $productOptionTotalTaxAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTotalTaxAmount += $productOptionTransfer->getSumTaxAmount();
        }

        return $productOptionTotalTaxAmount;
    }
}
