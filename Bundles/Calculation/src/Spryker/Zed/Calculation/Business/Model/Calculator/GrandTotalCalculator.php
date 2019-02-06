<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Service\Calculation\CalculationServiceInterface;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilTextInterface;

class GrandTotalCalculator implements CalculatorInterface
{
    use ShipmentAwareTrait;

    /**
     * @var \Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Service\Calculation\CalculationServiceInterface
     */
    protected $calculationService;

    /**
     * @param \Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilTextInterface $utilTextService
     * @param \Spryker\Service\Calculation\CalculationServiceInterface $calculationService
     */
    public function __construct(
        CalculationToUtilTextInterface $utilTextService,
        CalculationServiceInterface $calculationService
    ) {
        $this->utilTextService = $utilTextService;
        $this->calculationService = $calculationService;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $grandTotal = 0;
        $grandTotal = $this->calculateItemGrandTotal($calculableObjectTransfer, $grandTotal);
        $grandTotal = $this->calculateExpenseGrandTotal($calculableObjectTransfer, $grandTotal);
        $grandTotal = $this->calculateExpenseItemGrandTotal($calculableObjectTransfer, $grandTotal);

        $totalsTransfer = $calculableObjectTransfer->getTotals();
        $totalsTransfer->setHash($this->generateTotalsHash($grandTotal));

        $calculableObjectTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $grandTotal
     *
     * @return int
     */
    protected function calculateItemGrandTotal(CalculableObjectTransfer $calculableObjectTransfer, $grandTotal)
    {
        $shipmentGroups = $this->calculationService->groupItemsByShipment($calculableObjectTransfer->getItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($this->assertShipmentGroupHasNoExpense($shipmentGroupTransfer)) {
                continue;
            }

            $expenseTransfer = $shipmentGroupTransfer->getShipment()->getExpense();
            $grandTotal += $expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getCanceledAmount();
        }

        return $grandTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $grandTotal
     *
     * @return int
     */
    protected function calculateExpenseGrandTotal(CalculableObjectTransfer $calculableObjectTransfer, $grandTotal)
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            $grandTotal += $expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getCanceledAmount();
        }
        return $grandTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $grandTotal
     *
     * @return int
     */
    protected function calculateExpenseItemGrandTotal(CalculableObjectTransfer $calculableObjectTransfer, int $grandTotal)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $grandTotal += $itemTransfer->getSumPriceToPayAggregation() - $itemTransfer->getCanceledAmount();
        }

        return $grandTotal;
    }

    /**
     * @param int $grandTotal
     *
     * @return string
     */
    protected function generateTotalsHash($grandTotal)
    {
        return $this->utilTextService->hashValue($grandTotal, Hash::SHA256);
    }
}
