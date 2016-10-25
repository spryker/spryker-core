<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

class ItemProductOptionTaxWithDiscounts implements OrderAmountAggregatorInterface, CalculatorInterface
{

    /**
     * @var \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->addTaxWithProductOptions($orderTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->addTaxWithProductOptions($quoteTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function addTaxWithProductOptions(\ArrayObject $items)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();
        foreach ($items as $itemTransfer) {

            $unitOptionTaxTotalAmount = $this->getProductOptionWithDiscountsUnitTotalTaxAmount($itemTransfer);
            $sumOptionTaxTotalAmount = $this->getProductOptionWithDiscountsSumTotalTaxAmount($itemTransfer);

            $itemUnitAmount = 0;
            $itemSumTaxAmount = 0;

            if ($itemTransfer->getTaxRate()) {
                $itemUnitAmount = $this->calculateTaxAmount(
                    $itemTransfer->getUnitGrossPriceWithDiscounts(),
                    $itemTransfer->getTaxRate()
                );

                $itemSumTaxAmount = $this->calculateTaxAmount(
                    $itemTransfer->getSumGrossPriceWithDiscounts(),
                    $itemTransfer->getTaxRate()
                );
            }

            $itemTransfer->setUnitTaxAmountWithProductOptionAndDiscountAmounts($itemUnitAmount + $unitOptionTaxTotalAmount);
            $itemTransfer->setSumTaxAmountWithProductOptionAndDiscountAmounts($itemSumTaxAmount + $sumOptionTaxTotalAmount);

        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getProductOptionWithDiscountsUnitTotalTaxAmount(ItemTransfer $itemTransfer)
    {
        $unitOptionTaxTotalAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionTransfer->setUnitTaxAmountWithDiscounts(0);
            if (!$productOptionTransfer->getTaxRate()) {
                continue;
            }

            $unitOptionTaxAmount = $this->calculateTaxAmount(
                $productOptionTransfer->getUnitGrossPriceWithDiscounts(),
                $productOptionTransfer->getTaxRate()
            );

            $unitOptionTaxTotalAmount += $unitOptionTaxAmount;

            $productOptionTransfer->setUnitTaxAmountWithDiscounts($unitOptionTaxAmount);
        }

        return $unitOptionTaxTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getProductOptionWithDiscountsSumTotalTaxAmount(ItemTransfer $itemTransfer)
    {
        $sumOptionTaxTotalAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionTransfer->setSumTaxAmountWithDiscounts(0);
            if (!$productOptionTransfer->getTaxRate()) {
                continue;
            }

            $sumOptionTaxAmount = $this->calculateTaxAmount(
                $productOptionTransfer->getSumGrossPriceWithDiscounts(),
                $productOptionTransfer->getTaxRate()
            );

            $sumOptionTaxTotalAmount += $sumOptionTaxAmount;

            $productOptionTransfer->setSumTaxAmountWithDiscounts($sumOptionTaxAmount);
        }

        return $sumOptionTaxTotalAmount;
    }

    /**
     * @param int $price
     * @param float $taxRate
     *
     * @return float
     */
    protected function calculateTaxAmount($price, $taxRate)
    {
        return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($price, $taxRate);
    }

}
