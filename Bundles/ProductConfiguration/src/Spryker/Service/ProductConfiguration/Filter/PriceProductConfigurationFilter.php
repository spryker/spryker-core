<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\Filter;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductConfigurationFilter implements PriceProductConfigurationFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterProductConfigurationPrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        if (!$priceProductFilterTransfer->getProductConfigurationInstance()) {
            return $this->filterOutProductConfigurationPrices($priceProductTransfers);
        }

        $productConfigurationPriceProductTransfers = $this->filterOutPricesExceptCurrentProductConfigurationInstancePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        if ($productConfigurationPriceProductTransfers !== []) {
            return $productConfigurationPriceProductTransfers;
        }

        return $this->filterOutProductConfigurationPrices($priceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterOutProductConfigurationPrices(array $priceProductTransfers): array
    {
        return array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getPriceDimensionOrFail()->getProductConfigurationInstanceHash() === null;
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterOutPricesExceptCurrentProductConfigurationInstancePrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return array_filter($priceProductTransfers, function ($priceProductTransfer) use ($priceProductFilterTransfer) {
            if (!$priceProductTransfer->getPriceDimension()) {
                return false;
            }

            foreach ($priceProductFilterTransfer->getProductConfigurationInstanceOrFail()->getPrices() as $productConfigurationPriceProductTransfer) {
                if ($productConfigurationPriceProductTransfer->getPriceDimensionOrFail()->getProductConfigurationInstanceHash() === $priceProductTransfer->getPriceDimensionOrFail()->getProductConfigurationInstanceHash()) {
                    return true;
                }
            }

            return false;
        });
    }
}
