<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferPersistenceFactory getFactory()
 */
class ProductOfferEntityManager extends AbstractEntityManager implements ProductOfferEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOffer(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferEntity = $this->getFactory()
            ->createProductOfferPropelQuery()
            ->filterByIdProductOffer($productOfferTransfer->getIdProductOffer())
            ->findOneOrCreate();

        $productOfferEntity = $this->getFactory()
            ->createProductOfferMapper()
            ->mapProductOfferTransferToProductOfferEntity($productOfferTransfer, $productOfferEntity);
        $productOfferEntity->save();

        return $this->getFactory()
            ->createProductOfferMapper()
            ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, $productOfferTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function updateProductOffer(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferEntity = $this->getFactory()
            ->createProductOfferPropelQuery()
            ->findOneByIdProductOffer($productOfferTransfer->getIdProductOffer());

        $productOfferEntity = $this->getFactory()
            ->createProductOfferMapper()
            ->mapProductOfferTransferToProductOfferEntity($productOfferTransfer, $productOfferEntity);
        $productOfferEntity->save();

        return $this->getFactory()
            ->createProductOfferMapper()
            ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, $productOfferTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferStores(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer->requireIdProductOffer();

        foreach ($productOfferTransfer->getStores() as $storeTransfer) {
            $productOfferStoreEntity = (new SpyProductOfferStore())
                ->setFkProductOffer($productOfferTransfer->getIdProductOffer())
                ->setFkStore($storeTransfer->getIdStore());

            $productOfferStoreEntity->save();
        }

        return $productOfferTransfer;
    }

    /**
     * @param int $idProductOffer
     * @param int[] $storeIds
     *
     * @return void
     */
    public function deleteProductOfferStores(int $idProductOffer, array $storeIds): void
    {
        $this->getFactory()
            ->createProductOfferStoreQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->filterByFkStore_In($storeIds)
            ->find()
            ->delete();
    }
}
