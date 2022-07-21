<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Sorter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class PriceProductOfferStorageSorter implements PriceProductOfferStorageSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function sort(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): ProductOfferStorageCollectionTransfer
    {
        $productOffers = $productOfferStorageCollectionTransfer->getProductOffers()->getArrayCopy();
        usort($productOffers, function (ProductOfferStorageTransfer $firstProductOffer, ProductOfferStorageTransfer $secondProductOffer) {
            if (!$firstProductOffer->getPrice()) {
                return -1;
            }

            if (!$secondProductOffer->getPrice()) {
                return + 1;
            }

            return $firstProductOffer->getPriceOrFail()->getPrice() <=> $secondProductOffer->getPriceOrFail()->getPrice();
        });

        return $productOfferStorageCollectionTransfer->setProductOffers(new ArrayObject($productOffers));
    }
}
