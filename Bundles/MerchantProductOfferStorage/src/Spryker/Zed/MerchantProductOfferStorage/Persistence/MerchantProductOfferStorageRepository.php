<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
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
            ->joinSpyMerchant();

        $productOfferPropelQuery = $this->applyFilters($productOfferPropelQuery, $productOfferCriteriaFilterTransfer);

        $productOfferEntities = $productOfferPropelQuery->find();

        $productOfferStorageMapper = $this->getFactory()->createProductOfferStorageMapper();
        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferStorageMapper->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));
            $productOfferTransfer->setStores($productOfferStorageMapper->mapStoresByProductOfferEntity($productOfferEntity));

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

        if ($productOfferCriteriaFilterTransfer->getIsActiveConcreteProduct() !== null || $productOfferCriteriaFilterTransfer->getConcreteSkus()) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN
            );
            if ($productOfferCriteriaFilterTransfer->getIsActiveConcreteProduct() !== null) {
                $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteriaFilterTransfer->getIsActiveConcreteProduct());
            }
            if ($productOfferCriteriaFilterTransfer->getConcreteSkus()) {
                $productOfferQuery->addAnd(
                    $productOfferQuery->getNewCriterion(SpyProductTableMap::COL_SKU, $productOfferCriteriaFilterTransfer->getConcreteSkus(), Criteria::IN)
                );
            }
        }

        return $productOfferQuery;
    }
}
