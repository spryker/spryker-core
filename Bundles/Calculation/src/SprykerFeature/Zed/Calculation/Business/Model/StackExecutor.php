<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class StackExecutor
{

    /**
     * @param array $calculatorStack
     * @param OrderInterface $calculableContainer
     * @return OrderInterface
     */
    public function recalculate(array $calculatorStack, OrderInterface $calculableContainer)
    {
        foreach ($calculatorStack as $calculator) {
            if ($calculator instanceof CalculatorPluginInterface) {
                $calculator->recalculate($calculableContainer);
            }
            if ($calculator instanceof TotalsCalculatorPluginInterface) {
                $calculator->recalculateTotals(
                    $calculableContainer->getTotals(),
                    $calculableContainer,
                    $calculableContainer->getItems()
                );
            }
        }

        return $calculableContainer;
    }

    /**
     * @param array $calculatorStack
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return TotalsInterface
     */
    public function recalculateTotals(
        array $calculatorStack,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems = null
    ) {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setDiscount(new DiscountTotalsTransfer());

        $calculableItems = $calculableItems ? $calculableItems : $calculableContainer->getItems();
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
