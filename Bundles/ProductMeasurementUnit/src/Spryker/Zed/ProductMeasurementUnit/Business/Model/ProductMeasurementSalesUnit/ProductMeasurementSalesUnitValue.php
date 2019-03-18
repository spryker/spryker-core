<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilQuantityServiceInterface;

class ProductMeasurementSalesUnitValue implements ProductMeasurementSalesUnitValueInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(ProductMeasurementUnitToUtilQuantityServiceInterface $utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer): float
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
     * to be displayed sales unit is KG with a unit precision of 100 (exchanged value can be displayed up by utilQuantityService decimals),
     * and 2 KG represents 1 quantity (unit to availability conversion ratio is 0.5).
     * The retrieved normalized unit value is 16.00 (16.00 KG when displayed).
     *
     * @param float $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return float
     */
    protected function calculateNormalizedValue(
        float $availabilityValue,
        float $unitToAvailabilityConversion,
        int $unitPrecision
    ): float {
        $calculatedNormalizedValue = $availabilityValue / $unitToAvailabilityConversion * $unitPrecision;

        return $this->roundQuantity($calculatedNormalizedValue);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantitySalesUnit()) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->setValue(
                $this->calculateQuantityNormalizedSalesUnitValue($itemTransfer)
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param float $sumQuantities
     *
     * @return float
     */
    protected function roundQuantity(float $sumQuantities): float
    {
        return $this->utilQuantityService->roundQuantity($sumQuantities);
    }
}
