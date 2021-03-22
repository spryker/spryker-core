<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
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
                    ->setJoinType(Criteria::LEFT_JOIN)
            )
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductOfferTableMap::COL_FK_PRODUCT_OFFER])
                    ->setRight([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER])
                    ->setJoinType(Criteria::LEFT_JOIN)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): ArrayObject
    {
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

        $priceProductOfferEntities = $priceProductOfferQuery->find();

        return $this->getFactory()
            ->createPriceProductOfferMapper()
            ->mapPriceProductOfferEntitiesToPriceProductTransfers($priceProductOfferEntities, new ArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return int
     */
    public function count(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): int
    {
        $priceProductOfferQuery = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer()
            ->useSpyProductOfferQuery()
            ->endUse();

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
        if (
            $priceProductOfferCriteriaTransfer->getProductOfferCriteria()
            && $priceProductOfferCriteriaTransfer->getProductOfferCriteria()->getMerchantIds()
        ) {
            $priceProductOfferQuery->filterBy(
                SpyProductOfferTableMap::COL_FK_MERCHANT,
                $priceProductOfferCriteriaTransfer->getProductOfferCriteria()->getMerchantIds(),
                Criteria::IN
            );
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

        if ($priceProductOfferCriteriaTransfer->getPriceTypeIds()) {
            $priceProductOfferQuery->useSpyPriceProductStoreQuery()
                ->usePriceProductQuery()
                ->filterByFkPriceType_In($priceProductOfferCriteriaTransfer->getPriceTypeIds())
                ->endUse()
                ->endUse();
        }
    }
}
