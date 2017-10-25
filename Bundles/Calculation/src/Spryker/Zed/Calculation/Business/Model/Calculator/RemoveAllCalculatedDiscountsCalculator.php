<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;

class RemoveAllCalculatedDiscountsCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getItems() as $item) {
            foreach ($item->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->setCalculatedDiscounts(new ArrayObject());
            }
            $item->setCalculatedDiscounts(new ArrayObject());
        }

        foreach ($calculableObjectTransfer->getExpenses() as $expense) {
            $expense->setCalculatedDiscounts(new ArrayObject());
        }
    }
}
