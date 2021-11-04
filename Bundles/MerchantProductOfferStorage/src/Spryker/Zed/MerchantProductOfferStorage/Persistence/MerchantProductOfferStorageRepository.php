<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageRepository extends AbstractRepository implements MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param array<int> $merchantIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusByMerchantIds(array $merchantIds): array
    {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productOfferPropelQuery
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productOfferPropelQuery->getNewCriterion(SpyMerchantTableMap::COL_ID_MERCHANT, $merchantIds, Criteria::IN));

        return $productOfferPropelQuery
            ->select(SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->distinct()
            ->find()
            ->getData();
    }

    /**
     * @module Merchant
     * @module MerchantProductOffer
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOffers(ProductOfferCriteriaTransfer $productOfferCriteriaTransfer): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferPropelQuery = $this->getFactory()
            ->getProductOfferPropelQuery()
            ->joinWithSpyProductOfferStore()
            ->useSpyProductOfferStoreQuery()
                ->joinWithSpyStore()
            ->endUse()
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductOfferTransfer::ID_PRODUCT_CONCRETE)
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, ProductOfferTransfer::ID_PRODUCT_ABSTRACT);

        $productOfferPropelQuery = $this->applyFilters($productOfferPropelQuery, $productOfferCriteriaTransfer);

        $productOfferEntities = $productOfferPropelQuery->find();

        $productOfferStorageMapper = $this->getFactory()->createProductOfferStorageMapper();
        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferStorageMapper->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));
            foreach ($productOfferEntity->getSpyProductOfferStores() as $productOfferStoreEntity) {
                $productOfferTransfer->addStore(
                    $productOfferStorageMapper->mapStoreEntityToStoreTransfer($productOfferStoreEntity->getSpyStore(), new StoreTransfer()),
                );
            }
            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferCriteriaTransfer->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($productOfferCriteriaTransfer->getProductOfferReferences());
        }

        if ($productOfferCriteriaTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($productOfferCriteriaTransfer->getIsActive());
        }

        if ($productOfferCriteriaTransfer->getApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productOfferCriteriaTransfer->getApprovalStatuses());
        }

        if ($productOfferCriteriaTransfer->getIsActiveMerchant() !== null) {
            $productOfferQuery->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
                ->addAnd(SpyMerchantTableMap::COL_IS_ACTIVE, $productOfferCriteriaTransfer->getIsActiveMerchant());
        }

        if ($productOfferCriteriaTransfer->getIsActiveConcreteProduct() !== null) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN,
            );
            $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteriaTransfer->getIsActiveConcreteProduct());
        }

        if ($productOfferCriteriaTransfer->getConcreteSkus()) {
            $productOfferQuery->filterByConcreteSku_In($productOfferCriteriaTransfer->getConcreteSkus());
        }

        return $productOfferQuery;
    }
}
