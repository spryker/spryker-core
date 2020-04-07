<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductReader implements PriceProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getMinPriceProducts(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        if ($priceProductFilterTransfer->getQuantity() <= 1) {
            return array_filter($priceProductTransfers, [$this, 'isSingleItemPrice']);
        }

        $minPriceProductTransfer = $this->findMinPrice(
            $priceProductTransfers,
            $priceProductFilterTransfer->getQuantity()
        );

        if (!$minPriceProductTransfer) {
            return array_filter($priceProductTransfers, [$this, 'isSingleItemPrice']);
        }

        return [$minPriceProductTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param int $filterQuantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function findMinPrice(array $priceProductTransfers, int $filterQuantity): ?PriceProductTransfer
    {
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$priceProductTransfer->getVolumeQuantity() || $priceProductTransfer->getVolumeQuantity() > $filterQuantity) {
                continue;
            }

            if (!$minPriceProductTransfer) {
                $minPriceProductTransfer = $priceProductTransfer;

                continue;
            }

            $minPriceProductTransfer = $this->resolveLowestPrice($minPriceProductTransfer, $priceProductTransfer);
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $minPrice
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceToCompare
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveLowestPrice(PriceProductTransfer $minPrice, PriceProductTransfer $priceToCompare): PriceProductTransfer
    {
        if ($minPrice->getVolumeQuantity() > $priceToCompare->getVolumeQuantity()) {
            return $minPrice;
        }

        return $priceToCompare;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isSingleItemPrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantity() === null;
    }
}
