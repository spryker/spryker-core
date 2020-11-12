<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage\Filter;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductConfigurationFilter implements PriceProductConfigurationFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterProductConfigurationPrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        if (!$priceProductFilterTransfer->getProductConfigurationInstance()) {
            $priceProductTransfers = $this->filterOutProductConfigurationPrices($priceProductTransfers);

            return $priceProductTransfers;
        }

        $productConfigurationPriceProductTransfers = $this->filterOutPricesExceptCurrentProductConfigurationInstancePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        if ($productConfigurationPriceProductTransfers !== []) {
            return $productConfigurationPriceProductTransfers;
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function filterOutProductConfigurationPrices(array $priceProductTransfers): array
    {
        return array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash() === null;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function filterOutPricesExceptCurrentProductConfigurationInstancePrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return array_filter($priceProductTransfers, function ($priceProductTransfer) use ($priceProductFilterTransfer) {
            if (!$priceProductTransfer->getPriceDimension()) {
                return false;
            }

            return $priceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash()
                === $priceProductFilterTransfer->getProductConfigurationInstance()->getProductConfigurationHash();
        });
    }
}
