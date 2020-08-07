<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
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
     * @param int[] $merchantIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusByMerchantIds(array $merchantIds): array
    {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productOfferPropelQuery
            ->useSpyMerchantQuery()
                ->filterByIdMerchant_In($merchantIds)
            ->endUse();

        return $productOfferPropelQuery
            ->select(SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->distinct()
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOffersByFilterCriteria(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferPropelQuery = $this->getFactory()
            ->getProductOfferPropelQuery()
            ->joinWithSpyMerchant()
            ->joinWithSpyProductOfferStore()
            ->useSpyProductOfferStoreQuery()
                ->joinWithSpyStore()
            ->endUse()
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductOfferTransfer::ID_PRODUCT_CONCRETE)
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, ProductOfferTransfer::ID_PRODUCT_ABSTRACT);

        $productOfferPropelQuery = $this->applyFilters($productOfferPropelQuery, $productOfferCriteriaFilterTransfer);

        $productOfferEntities = $productOfferPropelQuery->find();

        $productOfferStorageMapper = $this->getFactory()->createProductOfferStorageMapper();
        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferStorageMapper->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));
            foreach ($productOfferEntity->getSpyProductOfferStores() as $productOfferStoreEntity) {
                $productOfferTransfer->addStore(
                    $productOfferStorageMapper->mapStoreEntityToStoreTransfer($productOfferStoreEntity->getSpyStore(), new StoreTransfer())
                );
            }
            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
    ): SpyProductOfferQuery {
        if ($productOfferCriteriaFilterTransfer->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($productOfferCriteriaFilterTransfer->getProductOfferReferences());
        }

        if ($productOfferCriteriaFilterTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($productOfferCriteriaFilterTransfer->getIsActive());
        }

        if ($productOfferCriteriaFilterTransfer->getApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productOfferCriteriaFilterTransfer->getApprovalStatuses());
        }

        if ($productOfferCriteriaFilterTransfer->getIsActiveMerchant() !== null) {
            $productOfferQuery->where(SpyMerchantTableMap::COL_IS_ACTIVE . ' = ?', $productOfferCriteriaFilterTransfer->getIsActiveMerchant());
        }

        if ($productOfferCriteriaFilterTransfer->getIsActiveConcreteProduct() !== null) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN
            );
            $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteriaFilterTransfer->getIsActiveConcreteProduct());
        }

        if ($productOfferCriteriaFilterTransfer->getConcreteSkus()) {
            $productOfferQuery->filterByConcreteSku_In($productOfferCriteriaFilterTransfer->getConcreteSkus());
        }

        return $productOfferQuery;
    }
}
