<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilPriceServiceInterface;

class SumGrossPriceCalculator implements CalculatorInterface
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
            $sumGrossPrice = $this->roundPrice(
                $expenseTransfer->getUnitGrossPrice() * $expenseTransfer->getQuantity()
            );

            $expenseTransfer->setSumGrossPrice($sumGrossPrice);
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
    protected function addCalculatedItemGrossAmounts(ItemTransfer $itemTransfer)
    {
        $this->assertItemRequirements($itemTransfer);

        if ($itemTransfer->getIsOrdered() === true) {
            return;
        }

        $sumGrossPrice = $this->roundPrice(
            $itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity()
        );

        $itemTransfer->setSumGrossPrice($sumGrossPrice);
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
            $this->addCalculatedItemGrossAmounts($itemTransfer);
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionPriceCalculationRequirements($productOptionTransfer);

                if ($productOptionTransfer->getIsOrdered() === true) {
                    continue;
                }

                $sumGrossPrice = $this->roundPrice(
                    $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
                );

                $productOptionTransfer->setSumGrossPrice($sumGrossPrice);
            }
        }
    }
}
