<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class StackExecutor
{

    /**
     * @param array $calculatorStack
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(array $calculatorStack, CalculableInterface $calculableContainer)
    {
        foreach ($calculatorStack as $calculator) {
            if ($calculator instanceof CalculatorPluginInterface) {
                $calculator->recalculate($calculableContainer);
            }
            if ($calculator instanceof TotalsCalculatorPluginInterface) {
                $calculator->recalculateTotals(
                    $calculableContainer->getCalculableObject()->getTotals(),
                    $calculableContainer,
                    $calculableContainer->getCalculableObject()->getItems()
                );
            }
        }

        return $calculableContainer;
    }

    /**
     * @param array $calculatorStack
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function recalculateTotals(
        array $calculatorStack,
        //OrderTransfer $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems = null
    ) {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setDiscount(new DiscountTotalsTransfer());

        $calculableItems = $calculableItems ? $calculableItems : $calculableContainer->getCalculableObject()->getItems();
        if ($calculableItems instanceof OrderItemsTransfer) {
            $calculableItems = $calculableItems->getOrderItems();
        }
        foreach ($calculatorStack as $calculator) {
            if ($calculator instanceof TotalsCalculatorPluginInterface) {
                $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
            }
        }

        return $totalsTransfer;
    }

}
