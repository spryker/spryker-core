<?php

namespace SprykerFeature\Zed\Calculation\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;

class StackExecutor
{

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

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
     * @param CalculableItemCollectionInterface $calculableItems
     * @return TotalsInterface
     */
    public function recalculateTotals(
        array $calculatorStack,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems = null
    ) {
        $totalsTransfer = new \Generated\Shared\Transfer\CalculationTotalsTransfer();
        $calculableItems = $calculableItems ? $calculableItems : $calculableContainer->getItems();
        foreach ($calculatorStack as $calculator) {
            if ($calculator instanceof TotalsCalculatorPluginInterface) {
                $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
            }
        }

        return $totalsTransfer;
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }
}
