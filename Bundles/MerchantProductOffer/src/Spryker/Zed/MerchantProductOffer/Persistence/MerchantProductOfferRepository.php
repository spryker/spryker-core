<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return array<int>
     */
    public function getProductOfferIds(MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer): array
    {
        $productOfferQuery = $this->applyMerchantProductOfferFilters(
            $merchantProductOfferCriteriaTransfer,
            $this->getFactory()->getProductOfferPropelQuery(),
        );

        $paginationTransfer = $merchantProductOfferCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productOfferQuery = $this->applyMerchantProductOfferPagination($productOfferQuery, $paginationTransfer);
        }

        $productOfferQuery->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER]);

        return $productOfferQuery->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFiltersDeprecated(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer,
        SpyProductOfferQuery $productOfferQuery
    ): SpyProductOfferQuery {
        if ($merchantProductOfferCriteriaTransfer->getSkus()) {
            $productOfferQuery->filterByConcreteSku_In($merchantProductOfferCriteriaTransfer->getSkus());
        }

        if ($merchantProductOfferCriteriaTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($merchantProductOfferCriteriaTransfer->getIsActive());
        }

        if ($merchantProductOfferCriteriaTransfer->getMerchantReference()) {
            $productOfferQuery
                ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
                ->addAnd(
                    $productOfferQuery->getNewCriterion(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, $merchantProductOfferCriteriaTransfer->getMerchantReference(), Criteria::EQUAL),
                );
        }

        if ($merchantProductOfferCriteriaTransfer->getStoreIds()) {
            $productOfferQuery
                ->useSpyProductOfferStoreQuery()
                    ->filterByFkStore_In($merchantProductOfferCriteriaTransfer->getStoreIds())
                ->endUse();
        }

        return $productOfferQuery;
    }

    /**
     * @module ProductOffer
     * @module Merchant
     *
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyMerchantProductOfferFilters(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer,
        SpyProductOfferQuery $productOfferQuery
    ): SpyProductOfferQuery {
        $merchantProductOfferConditionsTransfer = $merchantProductOfferCriteriaTransfer->getMerchantProductOfferConditions();
        if ($merchantProductOfferConditionsTransfer === null) {
            return $this->applyFiltersDeprecated($merchantProductOfferCriteriaTransfer, $productOfferQuery);
        }

        if ($merchantProductOfferConditionsTransfer->getSkus()) {
            $productOfferQuery->filterByConcreteSku_In($merchantProductOfferConditionsTransfer->getSkus());
        }

        if ($merchantProductOfferConditionsTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($merchantProductOfferConditionsTransfer->getIsActive());
        }

        if ($merchantProductOfferConditionsTransfer->getMerchantReferences()) {
            $productOfferQuery
                ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
                ->addAnd(
                    $productOfferQuery->getNewCriterion(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, $merchantProductOfferConditionsTransfer->getMerchantReferences(), Criteria::IN),
                );
        }

        if ($merchantProductOfferConditionsTransfer->getStoreIds()) {
            $productOfferQuery
                ->useSpyProductOfferStoreQuery()
                    ->filterByFkStore_In($merchantProductOfferConditionsTransfer->getStoreIds())
                ->endUse();
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyMerchantProductOfferPagination(
        SpyProductOfferQuery $productOfferQuery,
        PaginationTransfer $paginationTransfer
    ): SpyProductOfferQuery {
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productOfferQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        return $productOfferQuery;
    }
}
