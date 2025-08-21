<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferRepository extends AbstractRepository implements PriceProductOfferRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function createQueryCriteriaTransfer(): QueryCriteriaTransfer
    {
        return (new QueryCriteriaTransfer())
            ->setWithColumns([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => PriceProductDimensionTransfer::PRODUCT_OFFER_REFERENCE,
            ])
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE])
                    ->setRight([SpyPriceProductOfferTableMap::COL_FK_PRICE_PRODUCT_STORE])
                    ->setJoinType(Criteria::LEFT_JOIN),
            )
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductOfferTableMap::COL_FK_PRODUCT_OFFER])
                    ->setRight([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER])
                    ->setJoinType(Criteria::LEFT_JOIN),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): ArrayObject
    {
        /** @var \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery $priceProductOfferQuery */
        $priceProductOfferQuery = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyPriceProductStore()
            ->useSpyPriceProductStoreQuery()
                ->joinWithPriceProduct()
                ->joinWithStore()
                ->joinWithCurrency()
                ->usePriceProductQuery()
                    ->joinWithPriceType()
                ->endUse()
            ->endUse();

        $this->applyCriteria($priceProductOfferQuery, $priceProductOfferCriteriaTransfer);

        /** @var \Propel\Runtime\Collection\ArrayCollection $priceProductOfferDataCollection */
        $priceProductOfferDataCollection = $priceProductOfferQuery
            ->setFormatter(ArrayFormatter::class)
            ->find();

        return $this->getFactory()
            ->createPriceProductOfferMapper()
            ->mapPriceProductOfferDataCollectionToPriceProductTransfers(
                $priceProductOfferDataCollection,
                new ArrayObject(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return int
     */
    public function count(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): int
    {
        /** @var \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery $priceProductOfferQuery */
        $priceProductOfferQuery = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer();

        $this->applyCriteria($priceProductOfferQuery, $priceProductOfferCriteriaTransfer);

        return $priceProductOfferQuery->count();
    }

    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery $priceProductOfferQuery
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return void
     */
    protected function applyCriteria(
        SpyPriceProductOfferQuery $priceProductOfferQuery,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): void {
        if ($priceProductOfferCriteriaTransfer->getPriceProductOfferIds()) {
            $priceProductOfferQuery->filterByIdPriceProductOffer_In($priceProductOfferCriteriaTransfer->getPriceProductOfferIds());
        }

        if ($priceProductOfferCriteriaTransfer->getProductOfferCriteria()) {
            /** @var \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer */
            $productOfferCriteriaTransfer = $priceProductOfferCriteriaTransfer->getProductOfferCriteria();

            if ($productOfferCriteriaTransfer->getProductOfferReferences()) {
                $priceProductOfferQuery->useSpyProductOfferQuery()
                    ->filterByProductOfferReference_In(
                        $productOfferCriteriaTransfer->getProductOfferReferences(),
                    )
                    ->endUse();
            }

            if ($productOfferCriteriaTransfer->getProductOfferReference()) {
                $priceProductOfferQuery->useSpyProductOfferQuery()
                    ->filterByProductOfferReference(
                        $productOfferCriteriaTransfer->getProductOfferReference(),
                    )
                    ->endUse();
            }
        }

        if ($priceProductOfferCriteriaTransfer->getIdProductOffer()) {
            $priceProductOfferQuery->filterByFkProductOffer($priceProductOfferCriteriaTransfer->getIdProductOffer());
        }

        if ($priceProductOfferCriteriaTransfer->getCurrencyIds()) {
            $priceProductOfferQuery->useSpyPriceProductStoreQuery()
                ->useCurrencyQuery()
                ->filterByIdCurrency_In($priceProductOfferCriteriaTransfer->getCurrencyIds())
                ->endUse()
                ->endUse();
        }

        if ($priceProductOfferCriteriaTransfer->getStoreIds()) {
            $priceProductOfferQuery->useSpyPriceProductStoreQuery()
                ->useStoreQuery()
                ->filterByIdStore_In($priceProductOfferCriteriaTransfer->getStoreIds())
                ->endUse()
                ->endUse();
        }

        if ($priceProductOfferCriteriaTransfer->getPriceProductStoreIds()) {
            $priceProductOfferQuery->useSpyPriceProductStoreQuery()
                    ->filterByIdPriceProductStore_In($priceProductOfferCriteriaTransfer->getPriceProductStoreIds())
                ->endUse();
        }

        if ($priceProductOfferCriteriaTransfer->getPriceTypeIds()) {
            $priceProductOfferQuery->useSpyPriceProductStoreQuery()
                ->usePriceProductQuery()
                ->filterByFkPriceType_In($priceProductOfferCriteriaTransfer->getPriceTypeIds())
                ->endUse()
                ->endUse();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    public function getPriceProductOfferCollection(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): PriceProductOfferCollectionTransfer
    {
        $priceProductOfferCollectionTransfer = new PriceProductOfferCollectionTransfer();
        $priceProductOfferQuery = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer();

        $paginationTransfer = $priceProductOfferCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $priceProductOfferQuery = $this->applyPriceProductOfferPagination($priceProductOfferQuery, $paginationTransfer);
            $priceProductOfferCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createPriceProductOfferMapper()
            ->mapPriceProductOfferEntitiesToPriceProductOfferCollectionTransfer(
                $priceProductOfferQuery->find(),
                $priceProductOfferCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery $priceProductOfferQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery
     */
    protected function applyPriceProductOfferPagination(
        SpyPriceProductOfferQuery $priceProductOfferQuery,
        PaginationTransfer $paginationTransfer
    ): SpyPriceProductOfferQuery {
        $paginationTransfer->setNbResults($priceProductOfferQuery->count());
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $priceProductOfferQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $priceProductOfferQuery;
    }
}
