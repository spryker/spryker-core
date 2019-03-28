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
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilPriceServiceInterface;

class SumNetPriceCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @param \Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilPriceServiceInterface $utilPriceService
     */
    public function __construct(CalculationToUtilPriceServiceInterface $utilPriceService)
    {
        $this->utilPriceService = $utilPriceService;
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
        $this->calculateItemGrossAmountForItems($calculableObjectTransfer->getItems());
        $this->calculateSumGrossPriceForExpenses($calculableObjectTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateSumGrossPriceForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            if ($expenseTransfer->getIsOrdered() === true) {
                continue;
            }

            $sumNetPrice = $expenseTransfer->getUnitNetPrice() * $expenseTransfer->getQuantity();

            $expenseTransfer->setSumNetPrice($sumNetPrice);
        }
    }

    /**
     * @param float $price
     *
     * @return int
     */
    protected function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
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

        $sumNetPrice = $this->roundPrice(
            $itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity()
        );

        $itemTransfer->setSumNetPrice($sumNetPrice);
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
    protected function calculateItemGrossAmountForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->addCalculatedItemNetAmounts($itemTransfer);
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionPriceCalculationRequirements($productOptionTransfer);

                if ($productOptionTransfer->getIsOrdered() === true) {
                    continue;
                }

                $sumNetPrice = $this->roundPrice(
                    $productOptionTransfer->getUnitNetPrice() * $productOptionTransfer->getQuantity()
                );
                $productOptionTransfer->setSumNetPrice($sumNetPrice);
            }
        }
    }
}
