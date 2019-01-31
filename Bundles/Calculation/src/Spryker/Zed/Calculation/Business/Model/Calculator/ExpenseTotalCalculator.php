<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Calculation\CalculationServiceInterface;

class ExpenseTotalCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Service\Calculation\CalculationServiceInterface
     */
    protected $calculationService;

    /**
     * @param \Spryker\Service\Calculation\CalculationServiceInterface $calculationService
     */
    public function __construct(CalculationServiceInterface $calculationService)
    {
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

        $expenseTotal = $this->calculateExpenseTotalSumPrice($calculableObjectTransfer->getExpenses());
        $expenseTotal += $this->calculateItemExpenseTotalSumPrice($calculableObjectTransfer->getItems());

        $calculableObjectTransfer->getTotals()->setExpenseTotal($expenseTotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function calculateExpenseTotalSumPrice(ArrayObject $expenses)
    {
        $expenseTotal = 0;
        foreach ($expenses as $expenseTransfer) {
            $expenseTotal += $expenseTransfer->getSumPrice();
        }
        return $expenseTotal;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function calculateItemExpenseTotalSumPrice(ArrayObject $items): int
    {
        $expenseTotal = 0;
        $shipmentGroups = $this->calculationService->groupItemsByShipment($items);

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($this->assertShipmentGroupHasNoExpense($shipmentGroupTransfer)) {
                continue;
            }

            $expenseTotal += $shipmentGroupTransfer->getShipment()->getExpense()->getSumPrice();
        }

        return $expenseTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function assertShipmentGroupHasNoExpense(ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        return $shipmentGroupTransfer->getShipment() === null || $shipmentGroupTransfer->getShipment()->getExpense() === null;
    }
}
