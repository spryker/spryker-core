<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class TaxRateAverageAggregator implements CalculatorInterface
{

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

            $unitPriceToPayAggregationNetPrice = $this->getUnitNetPriceToPayAggregationNetPrice($itemTransfer, $priceMode);
            $taxRateAverageAggregation = $this->calculateTaxRateAverage($itemTransfer, $unitPriceToPayAggregationNetPrice);

            $itemTransfer->setTaxRateAverageAggregation($taxRateAverageAggregation);

        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getUnitNetPriceToPayAggregationNetPrice(
        ItemTransfer $itemTransfer,
        $priceMode = CalculationPriceMode::PRICE_MODE_GROSS
    ) {

        if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
            return $itemTransfer->getUnitPriceToPayAggregation();
        }

        return $itemTransfer->getUnitPriceToPayAggregation() - $itemTransfer->getUnitTaxAmountFullAggregation();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $unitPriceToPayAggregationNetPrice
     *
     * @return float
     */
    protected function calculateTaxRateAverage(ItemTransfer $itemTransfer, $unitPriceToPayAggregationNetPrice)
    {
        if (!$unitPriceToPayAggregationNetPrice) {
            return 0;
        }

        return round(
            ($itemTransfer->getUnitPriceToPayAggregation() / $unitPriceToPayAggregationNetPrice - 1) * 100,
            2
        );
    }

}
