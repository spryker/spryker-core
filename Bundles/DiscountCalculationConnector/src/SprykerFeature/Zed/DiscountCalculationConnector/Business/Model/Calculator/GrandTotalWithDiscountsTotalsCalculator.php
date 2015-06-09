<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculatorInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationInterface;

class GrandTotalWithDiscountsTotalsCalculator implements TotalsCalculatorPluginInterface
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
     * @param DiscountCalculationToCalculationInterface $grandTotalsCalculator
     * @param DiscountTotalsCalculatorInterface $discountTotalsCalculator
     */
    public function __construct(
        DiscountCalculationToCalculationInterface $grandTotalsCalculator,
        DiscountTotalsCalculatorInterface $discountTotalsCalculator
    ) {
        $this->grandTotalsCalculator = $grandTotalsCalculator;
        $this->discountTotalsCalculator = $discountTotalsCalculator;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @ param OrderInterface $container
     * @param CalculableInterface $container
     * @param \ArrayObject $items
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        //OrderInterface $container,
        CalculableInterface $container,
        \ArrayObject $items
    ) {
        if ($container instanceof OrderInterface) {
            $grandTotal = $this->calculateGrandTotal($totalsTransfer, $container, $items);
            $grandTotal -= $this->getDiscount($totalsTransfer, $container, $items);
            $totalsTransfer->setGrandTotalWithDiscounts($grandTotal);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        //OrderInterface $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems
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
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    protected function getDiscount(
        TotalsInterface $totalsTransfer,
        //OrderInterface $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        if (!is_null($totalsTransfer->getDiscount()->getTotalAmount())) {
            return $totalsTransfer->getDiscount()->getTotalAmount();
        } else {
            return $this->discountTotalsCalculator->calculateDiscount($calculableContainer, $calculableItems);
        }
    }
}
