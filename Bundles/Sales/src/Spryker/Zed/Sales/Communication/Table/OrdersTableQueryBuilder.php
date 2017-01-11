<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Table;

use DateTime;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class OrdersTableQueryBuilder implements OrdersTableQueryBuilderInterface
{

    const FIELD_ITEM_STATE_NAMES_CSV = 'item_state_names_csv';
    const FIELD_NUMBER_OF_ORDER_ITEMS = 'number_of_order_items';
    const DATE_FILTER_DAY = 'day';
    const DATE_FILTER_WEEK = 'week';

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected $salesOrderQuery;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected $salesOrderItemQuery;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     */
    public function __construct(
        SpySalesOrderQuery $salesOrderQuery,
        SpySalesOrderItemQuery $salesOrderItemQuery
    ) {
        $this->salesOrderQuery = $salesOrderQuery;
        $this->salesOrderItemQuery = $salesOrderItemQuery;
    }

    /**
     * @param int|null $idOrderItemProcess
     * @param int|null $idOrderItemState
     * @param string|null $dateFilter
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function buildQuery($idOrderItemProcess, $idOrderItemState, $dateFilter)
    {
        $query = $this->salesOrderQuery;
        $query = $this->addItemStates($query);
        $query = $this->addItemCount($query);
        $query = $this->filter($query, $idOrderItemProcess, $idOrderItemState, $dateFilter);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addItemStates(SpySalesOrderQuery $query)
    {
        $subQuery = clone $this->salesOrderItemQuery;
        $subQuery
            ->joinWithState()
            ->addSelfSelectColumns()
            ->clearSelectColumns()
            ->withColumn(
                sprintf('GROUP_CONCAT(%s)', SpyOmsOrderItemStateTableMap::COL_NAME),
                static::FIELD_ITEM_STATE_NAMES_CSV
            )
            ->filterByFkSalesOrder(
                sprintf(
                    '%s = %s',
                    SpySalesOrderItemTableMap::COL_FK_SALES_ORDER,
                    SpySalesOrderTableMap::COL_ID_SALES_ORDER
                ),
                Criteria::CUSTOM
            )
            ->groupByFkSalesOrder();

        $query = $this->addSubQuery($query, $subQuery, static::FIELD_ITEM_STATE_NAMES_CSV);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addItemCount(SpySalesOrderQuery $query)
    {
        $subQuery = clone $this->salesOrderItemQuery;
        $subQuery
            ->addSelfSelectColumns()
            ->clearSelectColumns()
            ->withColumn(
                sprintf('COUNT(%s)', SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM),
                static::FIELD_NUMBER_OF_ORDER_ITEMS
            )
            ->filterByFkSalesOrder(
                sprintf(
                    '%s = %s',
                    SpySalesOrderItemTableMap::COL_FK_SALES_ORDER,
                    SpySalesOrderTableMap::COL_ID_SALES_ORDER
                ),
                Criteria::CUSTOM
            )
            ->groupByFkSalesOrder();

        $subQuery->setPrimaryTableName(SpySalesOrderItemTableMap::TABLE_NAME);

        $query = $this->addSubQuery($query, $subQuery, static::FIELD_NUMBER_OF_ORDER_ITEMS);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $subQuery
     * @param string $resultFieldName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSubQuery(SpySalesOrderQuery $query, ModelCriteria $subQuery, $resultFieldName)
    {
        $params = [];
        $query->withColumn(
            sprintf('(%s)', $subQuery->createSelectSql($params)),
            $resultFieldName
        );

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     * @param int|null $idOrderItemProcess
     * @param int|null $idOrderItemState
     * @param string|null $dateFilter
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function filter(SpySalesOrderQuery $query, $idOrderItemProcess, $idOrderItemState, $dateFilter)
    {
        $query = $this->filterByOrderItemProcess($query, $idOrderItemProcess, $idOrderItemState);
        $query = $this->filterByDateRange($query, $dateFilter);

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     * @param int|null $idOrderItemProcess
     * @param int|null $idOrderItemState
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function filterByOrderItemProcess(SpySalesOrderQuery $query, $idOrderItemProcess, $idOrderItemState)
    {
        if (!$idOrderItemProcess) {
            return $query;
        }

        $query
            ->useItemQuery()
                ->filterByFkOmsOrderProcess($idOrderItemProcess)
                ->filterByFkOmsOrderItemState($idOrderItemState)
            ->endUse();

        return $query;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     * @param string|null $dateFilter
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function filterByDateRange(SpySalesOrderQuery $query, $dateFilter)
    {
        if (!$dateFilter) {
            return $query;
        }

        if ($dateFilter === self::DATE_FILTER_DAY) {
            $query
                ->useItemQuery()
                    ->filterByLastStateChange(new DateTime('-1 day'), Criteria::GREATER_THAN)
                ->endUse();
        } elseif ($dateFilter === self::DATE_FILTER_WEEK) {
            $query
                ->useItemQuery()
                    ->filterByLastStateChange(new DateTime('-1 day'), Criteria::LESS_EQUAL)
                    ->filterByLastStateChange(new DateTime('-7 day'), Criteria::GREATER_EQUAL)
                ->endUse();
        } else {
            $query
                ->useItemQuery()
                    ->filterByLastStateChange(new DateTime('-7 day'), Criteria::LESS_THAN)
                ->endUse();
        }

        return $query;
    }

}
