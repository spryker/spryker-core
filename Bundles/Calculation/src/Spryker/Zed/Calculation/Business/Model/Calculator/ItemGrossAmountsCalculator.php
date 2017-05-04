<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        $this->assertItemRequirements($itemTransfer);
        $itemTransfer->setSumGrossPrice($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());

        $itemTransfer->setUnitPrice($itemTransfer->getUnitGrossPrice());
        $itemTransfer->setSumPrice($itemTransfer->getSumGrossPrice());

        $itemTransfer->setUnitItemTotal($itemTransfer->getUnitGrossPrice());
        $itemTransfer->setSumItemTotal($itemTransfer->getSumGrossPrice());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitGrossPrice()->requireQuantity();
    }

}
