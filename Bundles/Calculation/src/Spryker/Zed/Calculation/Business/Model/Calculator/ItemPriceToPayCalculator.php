<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ItemPriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $items = $this->getItems($calculableContainer);

        foreach ($items as $itemTransfer) {
            $this->calculatePriceToPay($itemTransfer);
            $this->calculatePriceToPayWithoutDiscounts($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculatePriceToPay(ItemTransfer $itemTransfer)
    {
        $priceToPay = $itemTransfer->getGrossPrice();
        $priceToPay -= $this->sumDiscounts($itemTransfer->getDiscounts());

        $priceToPay = $this->adjustPriceWhenEmpty($itemTransfer, $priceToPay);

        //@todo add item expenses to priceToPay
        $priceToPay += $this->sumOptions($itemTransfer->getProductOptions());

        $itemTransfer->setPriceToPay($priceToPay);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculatePriceToPayWithoutDiscounts(ItemTransfer $itemTransfer)
    {
        $priceToPayWithoutDiscount = $itemTransfer->getGrossPrice();
        $priceToPayWithoutDiscount += $this->sumOptionsGrossPrice($itemTransfer->getProductOptions());
        $itemTransfer->setPriceToPayWithoutDiscount($priceToPayWithoutDiscount);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DiscountTransfer[] $discounts
     *
     * @return int
     */
    protected function sumDiscounts(\ArrayObject $discounts)
    {
        $discountAmount = 0;
        foreach ($discounts as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function sumExpenses(\ArrayObject $expenses)
    {
        $expenseAmount = 0;
        foreach ($expenses as $expense) {
            $expenseAmount += $expense->getPriceToPay();
        }

        return $expenseAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $options
     *
     * @return int
     */
    protected function sumOptions(\ArrayObject $options)
    {
        $optionsAmount = 0;
        foreach ($options as $option) {
            $optionsAmount += $option->getPriceToPay();
        }

        return $optionsAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $options
     *
     * @return int
     */
    protected function sumOptionsGrossPrice(\ArrayObject $options)
    {
        $optionsAmount = 0;
        foreach ($options as $option) {
            $optionsAmount += $option->getGrossPrice();
        }

        return $optionsAmount;
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

    /**
     * //@todo why set to gross, why not keep 0?
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $priceToPay
     *
     * @return int
     */
    protected function adjustPriceWhenEmpty(ItemTransfer $itemTransfer, $priceToPay)
    {
        $priceToPay = max(0, $priceToPay);
        if ($priceToPay === 0) {
            $priceToPay = $itemTransfer->getGrossPrice();
        }

        return $priceToPay;
    }

}
