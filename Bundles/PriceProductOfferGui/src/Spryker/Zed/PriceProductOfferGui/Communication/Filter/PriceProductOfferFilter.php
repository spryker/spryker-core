<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Filter;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductOfferFilter implements PriceProductOfferFilterInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductOffer\PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER
     *
     * @var string
     */
    protected const DIMENSION_TYPE_PRODUCT_OFFER = 'PRODUCT_OFFER';

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterOutProductOfferPrices(array $priceProductTransfers): array
    {
        $filteredPriceProductTransfers = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$this->isPriceProductOfferPrice($priceProductTransfer)) {
                $filteredPriceProductTransfers[] = $priceProductTransfer;
            }
        }

        return $filteredPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPriceProductOfferPrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getPriceDimension()
            && $priceProductTransfer->getPriceDimensionOrFail()->getType() === static::DIMENSION_TYPE_PRODUCT_OFFER;
    }
}
