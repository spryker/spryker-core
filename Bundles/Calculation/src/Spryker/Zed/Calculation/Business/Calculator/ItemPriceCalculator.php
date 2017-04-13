<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemPriceCalculator implements CalculatorInterface
{

    /**
     * @var array|CalculatorInterface[]
     */
    protected $calculators;

    /**
     * @param array|CalculatorInterface[] $itemSumCalculators
     */
    public function __construct(array $itemSumCalculators)
    {
        $this->calculators = $itemSumCalculators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->recalculateItemSumPrices($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->setItemPriceBasedOnTaxMode($itemTransfer, $quoteTransfer->getTaxMode());
            $this->recalculateProductOptionPrices($itemTransfer, $quoteTransfer->getTaxMode());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $taxMode
     *
     * @return void
     */
    protected function setItemPriceBasedOnTaxMode(ItemTransfer $itemTransfer, $taxMode)
    {
        if ($taxMode == 'net') {
            $itemTransfer->setUnitPrice($itemTransfer->getUnitNetPrice());
            $itemTransfer->setSumPrice($itemTransfer->getSumNetPrice());
        } else {
            $itemTransfer->setUnitPrice($itemTransfer->getUnitGrossPrice());
            $itemTransfer->setSumPrice($itemTransfer->getSumGrossPrice());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function recalculateItemSumPrices(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->calculators as $calculator) {
            $calculator->recalculate($quoteTransfer);
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     * @param string $taxMode
     *
     * @return void
     */
    protected function recalculateProductOptionPrices(ItemTransfer $itemTransfer, $taxMode)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->setProductOptionPriceBasedOnTaxMode($productOptionTransfer, $taxMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param string $taxMode
     *
     * @return void
     */
    protected function setProductOptionPriceBasedOnTaxMode(ProductOptionTransfer $productOptionTransfer, $taxMode)
    {
        if ($taxMode == 'net') {
            $productOptionTransfer->setUnitPrice($productOptionTransfer->getUnitNetPrice());
            $productOptionTransfer->setSumPrice($productOptionTransfer->getSumNetPrice());
        } else {
            $productOptionTransfer->setUnitPrice($productOptionTransfer->getUnitGrossPrice());
            $productOptionTransfer->setSumPrice($productOptionTransfer->getSumGrossPrice());
        }
    }
}
