<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Calculation\CalculationTaxMode;
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
            $calculableObjectTransfer->getTaxMode()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxAverageAggregationForItems(ArrayObject $items, $taxMode)
    {
        foreach ($items as $itemTransfer) {

            $unitPriceToPayAggregationNetPrice = $this->getUnitNetPriceToPayAggregationNetPrice($itemTransfer, $taxMode);
            $taxRateAverageAggregation = $this->calculateTaxRateAverage($itemTransfer, $unitPriceToPayAggregationNetPrice);

            $itemTransfer->setTaxRateAverageAggregation($taxRateAverageAggregation);

        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $taxMode
     *
     * @return int
     */
    protected function getUnitNetPriceToPayAggregationNetPrice(
        ItemTransfer $itemTransfer,
        $taxMode = CalculationTaxMode::TAX_MODE_GROSS
    ) {

        if ($taxMode === CalculationTaxMode::TAX_MODE_NET) {
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
