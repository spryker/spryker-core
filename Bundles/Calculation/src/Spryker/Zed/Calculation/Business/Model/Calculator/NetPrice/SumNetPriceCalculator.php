<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator\NetPrice;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Calculation\CalculationServiceInterface;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class SumNetPriceCalculator implements CalculatorInterface
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
     * For already ordered entities, sum prices are acting as source of truth.
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateItemNetAmountForItems($calculableObjectTransfer->getItems());
        $this->calculateSumNetPriceForExpenses($calculableObjectTransfer->getExpenses());
        $this->calculateSumNetPriceForItemExpenses($calculableObjectTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateSumNetPriceForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            if ($expenseTransfer->getIsOrdered() === true) {
                continue;
            }

            $expenseTransfer->setSumNetPrice($expenseTransfer->getUnitNetPrice() * $expenseTransfer->getQuantity());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCalculatedItemNetAmounts(ItemTransfer $itemTransfer)
    {
        $this->assertItemRequirements($itemTransfer);

        if ($itemTransfer->getIsOrdered() === true) {
            return;
        }

        $itemTransfer->setSumNetPrice($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function assertProductOptionPriceCalculationRequirements(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->requireQuantity();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateItemNetAmountForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->addCalculatedItemNetAmounts($itemTransfer);
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionPriceCalculationRequirements($productOptionTransfer);

                if ($productOptionTransfer->getIsOrdered() === true) {
                    continue;
                }

                $productOptionTransfer->setSumNetPrice($productOptionTransfer->getUnitNetPrice() * $productOptionTransfer->getQuantity());
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateSumNetPriceForItemExpenses(ArrayObject $items): void
    {
        $shipmentGroups = $this->calculationService->groupItemsByShipment($items);

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($this->assertShipmentGroupHasNoExpense($shipmentGroupTransfer)) {
                continue;
            }

            $expenseTransfer = $shipmentGroupTransfer->getShipment()->getExpense();
            if ($expenseTransfer->getIsOrdered() === true) {
                continue;
            }

            $expenseTransfer->setSumNetPrice($expenseTransfer->getUnitNetPrice() * $expenseTransfer->getQuantity());
        }
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
