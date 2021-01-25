<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\Transfer\ProductOfferStoreTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOfferHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function haveProductOffer(array $seedData = []): ProductOfferTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = (new ProductOfferBuilder($seedData))->build();
        $productOfferTransfer->setIdProductOffer(null);

        if (isset($seedData[ProductOfferTransfer::STORES])) {
            $productOfferTransfer->setStores($seedData[ProductOfferTransfer::STORES]);
        }

        $productOfferTransfer = $this->getLocator()
            ->productOffer()
            ->facade()
            ->create($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStoreTransfer
     */
    public function haveProductOfferStore(
        ProductOfferTransfer $productOfferTransfer,
        StoreTransfer $storeTransfer
    ): ProductOfferStoreTransfer {
        $productOfferTransfer->requireIdProductOffer();
        $storeTransfer->requireIdStore();

        $productOfferStoreEntity = new SpyProductOfferStore();
        $productOfferStoreEntity->setFkProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferStoreEntity->setFkStore($storeTransfer->getIdStore());
        $productOfferStoreEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productOfferStoreEntity): void {
            $productOfferStoreEntity->delete();
        });

        $productOfferStoreTransfer = new ProductOfferStoreTransfer();

        return $productOfferStoreTransfer->fromArray($productOfferStoreEntity->toArray(), true);
    }
}
