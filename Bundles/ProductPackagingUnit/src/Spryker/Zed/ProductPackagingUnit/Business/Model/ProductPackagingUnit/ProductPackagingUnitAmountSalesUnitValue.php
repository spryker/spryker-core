<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductPackagingUnitAmountSalesUnitValue implements ProductPackagingUnitAmountSalesUnitValueInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateAmountSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountSalesUnit()) {
                continue;
            }

            $itemTransfer->getAmountSalesUnit()->setValue(
                $this->calculateAmountNormalizedSalesUnitValue($itemTransfer)
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateAmountNormalizedSalesUnitValue(ItemTransfer $itemTransfer): int
    {
        $itemTransfer
            ->requireAmountSalesUnit()
            ->requireAmount()
            ->requireQuantity()
            ->getAmountSalesUnit()
            ->requireConversion()
            ->requirePrecision();

        $amountPerQuantity = $itemTransfer->getAmount() / $itemTransfer->getQuantity();

        return $this->calculateNormalizedValue(
            (int)$amountPerQuantity,
            $itemTransfer->getAmountSalesUnit()->getConversion(),
            $itemTransfer->getAmountSalesUnit()->getPrecision()
        );
    }

    /**
     * @param float $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return int
     */
    protected function calculateNormalizedValue(float $availabilityValue, float $unitToAvailabilityConversion, int $unitPrecision): int
    {
        return (int)round(
            $this->calculateFloatNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
        );
    }

    /**
     * @param float $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return float
     */
    protected function calculateFloatNormalizedValue(float $availabilityValue, float $unitToAvailabilityConversion, int $unitPrecision): float
    {
        return $availabilityValue / $unitToAvailabilityConversion * $unitPrecision;
    }
}
