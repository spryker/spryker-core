<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
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
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffersStorage()->getArrayCopy();
        usort($productOfferStorageTransfers, function (ProductOfferStorageTransfer $firstProductOfferStorageTransfer, ProductOfferStorageTransfer $secondProductOfferStorageTransfer) {
            if (!$firstProductOfferStorageTransfer->getPrice()) {
                return -1;
            }

            if (!$secondProductOfferStorageTransfer->getPrice()) {
                return + 1;
            }

            return $firstProductOfferStorageTransfer->getPrice()->getPrice() <=> $secondProductOfferStorageTransfer->getPrice()->getPrice();
        });

        return $productOfferStorageCollectionTransfer->setProductOffersStorage(new ArrayObject($productOfferStorageTransfers));
    }
}
