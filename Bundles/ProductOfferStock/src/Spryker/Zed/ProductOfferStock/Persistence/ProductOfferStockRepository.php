<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Orm\Zed\ProductOfferStock\Persistence\Map\SpyProductOfferStockTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockPersistenceFactory getFactory()
 */
class ProductOfferStockRepository extends AbstractRepository implements ProductOfferStockRepositoryInterface
{
    protected const COLUMN_ALIAS_QUANTITY = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferStockForRequest(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal
    {
        $productOfferStockRequestTransfer->requireProductOfferReference()
            ->requireStore()
            ->getStore()
            ->requireIdStore();

        $quantity = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->useSpyProductOfferQuery()
            ->filterByProductOfferReference($productOfferStockRequestTransfer->getProductOfferReference())
            ->endUse()
            ->useStockQuery()
            ->useStockStoreQuery()
            ->filterByFkStore($productOfferStockRequestTransfer->getStore()->getIdStore())
            ->endUse()
            ->endUse()
            ->withColumn('SUM(' . SpyProductOfferStockTableMap::COL_QUANTITY . ')', static::COLUMN_ALIAS_QUANTITY)
            ->select([static::COLUMN_ALIAS_QUANTITY])
            ->findOne();

        return new Decimal($quantity ?: 0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer|null
     */
    public function findOne(
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
    ): ?ProductOfferStockTransfer {
        $productOfferStockEntity = $this->applyFilters(
            $this->getFactory()->getProductOfferStockPropelQuery(),
            $productOfferStockCriteriaFilterTransfer
        )->findOne();

        if (!$productOfferStockEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductOfferStockMapper()
            ->mapProductOfferStockEntityToProductOfferStockTransfer(
                $productOfferStockEntity,
                new ProductOfferStockTransfer()
            );
    }

    /**
     * @param \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery $productOfferStockQuery
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    protected function applyFilters(
        SpyProductOfferStockQuery $productOfferStockQuery,
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
    ): SpyProductOfferStockQuery {
        if ($productOfferStockCriteriaFilterTransfer->getFkProductOffer() !== null) {
            $productOfferStockQuery->filterByFkProductOffer(
                $productOfferStockCriteriaFilterTransfer->getFkProductOffer()
            );
        }

        return $productOfferStockQuery;
    }
}
