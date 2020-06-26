<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Sorter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class PriceProductStorageSorter implements PriceProductStorageSorterInterface
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
