<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageRepository extends AbstractRepository implements MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getActiveProductOffersByFilterCriteria(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->getProductOfferPropelQuery()
            ->joinWithSpyMerchant()
            ->filterByProductOfferReference_In($productOfferCriteriaFilterTransfer->getProductOfferReferences())
            ->filterByIsActive($productOfferCriteriaFilterTransfer->getIsActive())
            ->filterByApprovalStatus_In($productOfferCriteriaFilterTransfer->getApprovalStatuses());

        $productOfferQuery->addJoin(
            SpyProductOfferTableMap::COL_CONCRETE_SKU,
            SpyProductTableMap::COL_SKU,
            Criteria::INNER_JOIN
        );
        $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, true);

        $productOfferEntities = $productOfferQuery->find();

        $productOfferStorageMapper = $this->getFactory()->createProductOfferStorageMapper();
        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferStorageMapper->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));

            $productOfferTransfer->setStores($this->getStoresByProductOfferEntity($productOfferEntity));
            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $spyProductOfferEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]|\ArrayObject
     */
    protected function getStoresByProductOfferEntity(SpyProductOffer $spyProductOfferEntity): ArrayObject
    {
        $storeTransfers = [];
        $productOfferStorageMapper = $this->getFactory()->createProductOfferStorageMapper();
        foreach ($spyProductOfferEntity->getSpyStores() as $storeEntity) {
            $storeTransfers[] = $productOfferStorageMapper->mapStoreEntityToStoreTransfer(
                $storeEntity,
                new StoreTransfer()
            );
        }

        return new ArrayObject($storeTransfers);
    }
}
