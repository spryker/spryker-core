<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductMeasurementSalesUnitValue implements ProductMeasurementSalesUnitValueInterface
{
    /**
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer): int
    {
        $itemTransfer
            ->requireQuantitySalesUnit()
            ->requireQuantity()
            ->getQuantitySalesUnit()
                ->requireConversion()
                ->requirePrecision();

        return $this->calculateNormalizedValue(
            $itemTransfer->getQuantity(),
            $itemTransfer->getQuantitySalesUnit()->getConversion(),
            $itemTransfer->getQuantitySalesUnit()->getPrecision()
        );
    }

    /**
     * Converts a value (representing availability) to a given unit with a given unit precision.
     *
     * @example
     * 8 quantity is ordered (availability value),
     * to be displayed sales unit is KG with a unit precision of 100 (exchanged value can be displayed up to 2 decimals),
     * and 2 KG represents 1 quantity (unit to availability conversion ratio is 0.5).
     * The retrieved normalized unit value is 1600 (16.00 KG when displayed).
     *
     * @param int $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return int
     */
    protected function calculateNormalizedValue(int $availabilityValue, float $unitToAvailabilityConversion, int $unitPrecision): int
    {
        return (int)round(
            $this->calculateFloatNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
        );
    }

    /**
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param int $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return float
     */
    protected function calculateFloatNormalizedValue(int $availabilityValue, float $unitToAvailabilityConversion, int $unitPrecision): float
    {
        return $availabilityValue / $unitToAvailabilityConversion * $unitPrecision;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function normalizeSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->setValue(
                $this->calculateQuantityNormalizedSalesUnitValue($itemTransfer)
            );
        }

        return $quoteTransfer;
    }
}
