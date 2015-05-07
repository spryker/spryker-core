<?php

namespace SprykerFeature\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\CalculationTotalsTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class StackExecutor
{

    /**
     * @param array $calculatorStack
     * @param CalculableContainerInterface $calculableContainer
     * @return CalculableContainerInterface
     */
    public function recalculate(array $calculatorStack, CalculableContainerInterface $calculableContainer)
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
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     * @return TotalsInterface
     */
    public function recalculateTotals(
        array $calculatorStack,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems = null
    ) {
        $totalsTransfer = new CalculationTotalsTransfer();
        $calculableItems = $calculableItems ? $calculableItems : $calculableContainer->getItems();
        foreach ($calculatorStack as $calculator) {
            if ($calculator instanceof TotalsCalculatorPluginInterface) {
                $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
            }
        }

        return $totalsTransfer;
    }
}
