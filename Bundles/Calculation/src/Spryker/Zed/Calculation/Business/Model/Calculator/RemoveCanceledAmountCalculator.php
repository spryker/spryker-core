<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;

class RemoveCanceledAmountCalculator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
       $this->recalculateCanceledAmountForItems($calculableObjectTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function recalculateCanceledAmountForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->setCanceledAmount(0);
        }
    }
}
