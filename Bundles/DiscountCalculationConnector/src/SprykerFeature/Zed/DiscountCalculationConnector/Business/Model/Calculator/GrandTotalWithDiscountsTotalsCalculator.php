<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\AbstractCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculatorInterface;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

class GrandTotalWithDiscountsTotalsCalculator extends AbstractCalculator implements TotalsCalculatorPluginInterface
{
    /**
     * @var SubtotalTotalsCalculatorInterface
     */
    protected $grandTotalsCalculator;

    /**
     * @var DiscountTotalsCalculatorInterface
     */
    protected $discountTotalsCalculator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param DiscountCalculationToCalculationInterface $grandTotalsCalculator
     * @param DiscountTotalsCalculatorInterface $discountTotalsCalculator
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        DiscountCalculationToCalculationInterface $grandTotalsCalculator,
        DiscountTotalsCalculatorInterface $discountTotalsCalculator
    ) {
        parent::__construct($locator);
        $this->grandTotalsCalculator = $grandTotalsCalculator;
        $this->discountTotalsCalculator = $discountTotalsCalculator;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $container
     * @param CalculableItemCollectionInterface $items
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $container,
        CalculableItemCollectionInterface $items
    ) {
        if ($container instanceof DiscountableContainerInterface &&
        $items instanceof DiscountableItemCollectionInterface) {
            $grandTotal = $this->calculateGrandTotal($totalsTransfer, $container, $items);
            $grandTotal -= $this->getDiscount($totalsTransfer, $container, $items);
            $totalsTransfer->setGrandTotalWithDiscounts($grandTotal);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $this->grandTotalsCalculator->recalculateGrandTotalTotals(
            $totalsTransfer,
            $calculableContainer,
            $calculableItems
        );

        return $totalsTransfer->getGrandTotal();
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param DiscountableContainerInterface $calculableContainer
     * @param DiscountableItemCollectionInterface $calculableItems
     * @return int
     */
    protected function getDiscount(
        TotalsInterface $totalsTransfer,
        DiscountableContainerInterface $calculableContainer,
        DiscountableItemCollectionInterface $calculableItems
    ) {
        if ($totalsTransfer->getDiscount()->getTotalAmount() !== 0) {
            return $totalsTransfer->getDiscount()->getTotalAmount();
        } else {
            return $this->discountTotalsCalculator->calculateDiscount($calculableContainer, $calculableItems);
        }
    }
}
