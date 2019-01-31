<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Calculation\CalculationServiceInterface;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class PriceGrossCalculator implements CalculatorInterface
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
        $this->calculatePriceForItems($calculableObjectTransfer->getItems());
        $this->calculatePricesForExpenses($calculableObjectTransfer->getExpenses());
        $this->calculatePricesForItemExpenses($calculableObjectTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculatePriceForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->setUnitPrice($itemTransfer->getUnitGrossPrice());
            $itemTransfer->setSumPrice($itemTransfer->getSumGrossPrice());

            $this->recalculateProductOptionPrices($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function recalculateProductOptionPrices(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setUnitPrice($productOptionTransfer->getUnitGrossPrice());
            $productOptionTransfer->setSumPrice($productOptionTransfer->getSumGrossPrice());
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculatePricesForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setUnitPrice($expenseTransfer->getUnitGrossPrice());
            $expenseTransfer->setSumPrice($expenseTransfer->getSumGrossPrice());
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculatePricesForItemExpenses(ArrayObject $items): void
    {
        $shipmentGroups = $this->calculationService->groupItemsByShipment($items);

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($this->assertShipmentGroupHasNoExpense($shipmentGroupTransfer)) {
                continue;
            }

            $expenseTransfer = $shipmentGroupTransfer->getShipment()->getExpense();
            $expenseTransfer->setUnitPrice($expenseTransfer->getUnitGrossPrice());
            $expenseTransfer->setSumPrice($expenseTransfer->getSumGrossPrice());
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
