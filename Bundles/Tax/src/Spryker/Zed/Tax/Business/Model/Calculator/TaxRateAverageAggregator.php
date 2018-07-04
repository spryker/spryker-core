<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

class TaxRateAverageAggregator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateTaxAverageAggregationForItems(
            $calculableObjectTransfer->getItems(),
            $calculableObjectTransfer->getPriceMode()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $priceMode
     *
     * @return void
     */
    protected function calculateTaxAverageAggregationForItems(ArrayObject $items, $priceMode)
    {
        foreach ($items as $itemTransfer) {
            $netSumPriceToPayAggregation = $this->getNetSumPriceToPayAggregation($itemTransfer, $priceMode);
            $taxRateAverageAggregation = $this->calculateTaxRateAverage($itemTransfer, $netSumPriceToPayAggregation);

            $itemTransfer->setTaxRateAverageAggregation($taxRateAverageAggregation);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getNetSumPriceToPayAggregation(
        ItemTransfer $itemTransfer,
        $priceMode = CalculationPriceMode::PRICE_MODE_GROSS
    ) {

        if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
            return $itemTransfer->getSumPriceToPayAggregation();
        }

        $taxAmount = $this->calculateTax($itemTransfer);

        return $itemTransfer->getSumPriceToPayAggregation() - $taxAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $netSumPriceToPayAggregation
     *
     * @return float
     */
    protected function calculateTaxRateAverage(ItemTransfer $itemTransfer, $netSumPriceToPayAggregation)
    {
        if (!$netSumPriceToPayAggregation) {
            return 0;
        }

        return round(
            ($itemTransfer->getSumPriceToPayAggregation() / $netSumPriceToPayAggregation - 1) * 100,
            2
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateTax(ItemTransfer $itemTransfer)
    {
        $sumPriceAfterDiscounts = $itemTransfer->getSumPrice() - $itemTransfer->getSumDiscountAmountAggregation();
        $sumTaxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($sumPriceAfterDiscounts, $itemTransfer->getTaxRate(), false);

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $sumOptionPriceAfterDiscounts = $productOptionTransfer->getSumPrice() - $productOptionTransfer->getSumDiscountAmountAggregation();
            $sumOptionTax = $this->priceCalculationHelper->getTaxValueFromPrice($sumOptionPriceAfterDiscounts, $productOptionTransfer->getTaxRate(), false);
            $sumTaxAmount += $sumOptionTax;
        }

        return $sumTaxAmount;
    }
}
