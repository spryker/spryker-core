<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Persistence;

use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedPersistenceFactory getFactory()
 */
class AvailabilityDataFeedQueryContainer extends AbstractQueryContainer implements AvailabilityDataFeedQueryContainerInterface
{
    /**
     * @var string
     */
    public const UPDATED_FROM_CONDITION = 'UPDATED_FROM_CONDITION';

    /**
     * @var string
     */
    public const UPDATED_TO_CONDITION = 'UPDATED_TO_CONDITION';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityDataFeed(AvailabilityDataFeedTransfer $availabilityDataFeedTransfer)
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $availabilityProductQuery */
        $availabilityProductQuery = $this->getFactory()
            ->getAvailabilityQueryContainer()
            ->queryAvailabilityWithStockByIdLocale($availabilityDataFeedTransfer->getIdLocale());

        $availabilityProductQuery = $this->applyDateFilter($availabilityProductQuery, $availabilityDataFeedTransfer);

        return $availabilityProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $entityQuery
     * @param \Generated\Shared\Transfer\AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function applyDateFilter(
        SpyProductAbstractQuery $entityQuery,
        AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
    ) {
        if ($availabilityDataFeedTransfer->getUpdatedFrom()) {
            $entityQuery->condition(
                static::UPDATED_FROM_CONDITION,
                SpyProductAbstractTableMap::COL_UPDATED_AT . ' >= ?',
                $availabilityDataFeedTransfer->getUpdatedFrom(),
            )->where([static::UPDATED_FROM_CONDITION]);
        }

        if ($availabilityDataFeedTransfer->getUpdatedTo()) {
            $entityQuery->condition(
                static::UPDATED_TO_CONDITION,
                SpyProductAbstractTableMap::COL_UPDATED_AT . ' <= ?',
                $availabilityDataFeedTransfer->getUpdatedTo(),
            )->where([static::UPDATED_TO_CONDITION]);
        }

        return $entityQuery;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProductQuery $stockProductQuery
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    protected function applyGroupings(SpyStockProductQuery $stockProductQuery)
    {
        $stockProductQuery->groupBy(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT);

        return $stockProductQuery;
    }
}
