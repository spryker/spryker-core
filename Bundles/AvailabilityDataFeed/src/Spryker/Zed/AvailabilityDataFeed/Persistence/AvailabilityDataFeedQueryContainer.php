<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Persistence;

use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

/**
 * @method \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedPersistenceFactory getFactory()
 */
class AvailabilityDataFeedQueryContainer extends AbstractQueryContainer implements AvailabilityDataFeedQueryContainerInterface
{

    const TOUCH_ITEM_TYPE = 'stock-product';
    const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';
    const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';
    const JOIN_TOUCH_TABLE_CONDITION_NAME = 'JOIN_TOUCH_TABLE_CONDITION_NAME';

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     */
    protected $stockQueryContainer;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     */
    public function __construct(StockQueryContainerInterface $stockQueryContainer)
    {
        $this->stockQueryContainer = $stockQueryContainer;
    }

    /**
     * @api
     *
     * @param AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function getAvailabilityDataFeedQuery(AvailabilityDataFeedTransfer $availabilityDataFeedTransfer)
    {
        $stockProductQuery = $this->stockQueryContainer
            ->queryAllStockProducts();
        $stockProductQuery
            ->useStockQuery()
                ->useStockProductQuery()
                ->endUse()
            ->endUse();

        $stockProductQuery = $this->applyJoins($stockProductQuery, $availabilityDataFeedTransfer);
        $stockProductQuery = $this->applyDateFilter($stockProductQuery, $availabilityDataFeedTransfer);
        $stockProductQuery = $this->applyGroupings($stockProductQuery);

        return $stockProductQuery;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProductQuery $stockProductQuery
     * @param AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return SpyStockProductQuery
     */
    protected function applyJoins(
        SpyStockProductQuery $stockProductQuery,
        AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
    ) {
        $stockProductQuery = $this->joinProducts($stockProductQuery, $availabilityDataFeedTransfer);

        return $stockProductQuery;
    }

    /**
     * @param SpyStockProductQuery $stockProductQuery
     * @param AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return SpyStockProductQuery
     */
    protected function joinProducts(
        SpyStockProductQuery $stockProductQuery,
        AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
    ) {
        if ($availabilityDataFeedTransfer->getIsJoinProduct()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($availabilityDataFeedTransfer->getLocaleId());

            $stockProductQuery
                ->useSpyProductQuery()
                ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale(
                    $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                    $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                )
                ->endUse()
                ->endUse();
        }

        return $stockProductQuery;
    }

    /**
     * @param integer $localeId
     *
     * @return array
     */
    protected function getIdLocaleFilterConditions($localeId = null)
    {
        if ($localeId !== null) {
            $filterCriteria = Criteria::EQUAL;
            $filterValue = $localeId;
        } else {
            $filterCriteria = Criteria::NOT_EQUAL;
            $filterValue = null;
        }

        return [
            self::LOCALE_FILTER_VALUE => $filterValue,
            self::LOCALE_FILTER_CRITERIA => $filterCriteria,
        ];
    }

    /**
     * @param SpyStockProductQuery $productQuery
     *
     * @return SpyStockProductQuery
     */
    protected function joinTouchTable(SpyStockProductQuery $productQuery)
    {
        $productQuery->addJoin(
            SpyStockProductTableMap::COL_ID_STOCK_PRODUCT,
            SpyTouchTableMap::COL_ITEM_ID,
            Criteria::INNER_JOIN
        );
        $productQuery->condition(
            self::JOIN_TOUCH_TABLE_CONDITION_NAME,
            SpyTouchTableMap::COL_ITEM_TYPE . ' = ?',
            self::TOUCH_ITEM_TYPE,
            \PDO::PARAM_STR
        );
        $productQuery->where([self::JOIN_TOUCH_TABLE_CONDITION_NAME]);

        return $productQuery;
    }

    /**
     * @param SpyStockProductQuery $entityQuery
     * @param AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return SpyStockProductQuery
     */
    protected function applyDateFilter(
        SpyStockProductQuery $entityQuery,
        AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
    ) {

        $entityQuery = $this->joinTouchTable($entityQuery);

        if ($availabilityDataFeedTransfer->getUpdatedFrom() !== null) {
            $entityQuery->condition(
                'updatedFromCondition',
                SpyTouchTableMap::COL_TOUCHED . '> ?',
                $availabilityDataFeedTransfer->getUpdatedFrom(),
                \PDO::PARAM_STR
            );
            $entityQuery->where(['updatedFromCondition']);
        }

        if ($availabilityDataFeedTransfer->getUpdatedTo() !== null) {
            $entityQuery->condition(
                'updatedToCondition',
                SpyTouchTableMap::COL_TOUCHED . '< ?',
                $availabilityDataFeedTransfer->getUpdatedTo(),
                \PDO::PARAM_STR
            );
            $entityQuery->where(['updatedToCondition']);
        }

        return $entityQuery;
    }

    /**
     * @param SpyStockProductQuery $stockProductQuery
     *
     * @return SpyStockProductQuery
     */
    protected function applyGroupings(SpyStockProductQuery $stockProductQuery)
    {
        $stockProductQuery->groupBy(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT);

        return $stockProductQuery;
    }

}
