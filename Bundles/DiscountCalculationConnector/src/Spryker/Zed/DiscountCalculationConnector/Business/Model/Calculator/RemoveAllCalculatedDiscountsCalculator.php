<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class RemoveAllCalculatedDiscountsCalculator
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        foreach ($calculableContainer->getCalculableObject()->getItems() as $item) {
            $item->setDiscounts(new \ArrayObject());

            foreach ($item->getProductOptions() as $option) {
                $option->setDiscounts(new \ArrayObject());
            }
            foreach ($item->getExpenses() as $expense) {
                $expense->setDiscounts(new \ArrayObject());
            }
        }

        foreach ($calculableContainer->getCalculableObject()->getExpenses() as $expense) {
            $expense->setDiscounts(new \ArrayObject());
        }
    }

}
