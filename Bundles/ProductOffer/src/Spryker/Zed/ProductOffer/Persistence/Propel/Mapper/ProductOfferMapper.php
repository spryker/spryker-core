<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\Store\Persistence\SpyStore;

class ProductOfferMapper
{
    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function mapProductOfferEntityToProductOfferTransfer(
        SpyProductOffer $productOfferEntity,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        $productOfferTransfer = $productOfferTransfer->fromArray(
            $productOfferEntity->toArray(),
            true,
        );

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOffer
     */
    public function mapProductOfferTransferToProductOfferEntity(
        ProductOfferTransfer $productOfferTransfer,
        SpyProductOffer $productOfferEntity
    ): SpyProductOffer {
        $productOfferEntity->fromArray(
            $productOfferTransfer->modifiedToArray(false),
        );

        return $productOfferEntity;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(
        SpyStore $storeEntity,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param iterable<\Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore> $productOfferStoreEntities
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function mapProductOfferStoreEntitiesToStoreTransfers(iterable $productOfferStoreEntities): array
    {
        $storeTransfers = [];
        foreach ($productOfferStoreEntities as $productOfferStoreEntity) {
            $storeTransfers[] = $this->mapStoreEntityToStoreTransfer($productOfferStoreEntity->getSpyStore(), new StoreTransfer());
        }

        return $storeTransfers;
    }
}
