<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Table;

use DateTime;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria as SprykerCriteria;

class OrdersTableQueryBuilder implements OrdersTableQueryBuilderInterface
{
    const FIELD_ITEM_STATE_NAMES_CSV = 'item_state_names_csv';
    const FIELD_NUMBER_OF_ORDER_ITEMS = 'number_of_order_items';
    const DATE_FILTER_DAY = 'day';
    const DATE_FILTER_WEEK = 'week';
    const FIELD_ORDER_GRAND_TOTAL = 'order_grand_total';

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected $salesOrderQuery;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     */
    public function __construct(SpySalesOrderQuery $salesOrderQuery)
    {
        $this->salesOrderQuery = $salesOrderQuery;
    }

    /**
     * @param int|null $idOrderItemProcess
     * @param int|null $idOrderItemState
     * @param string|null $dateFilter
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function buildQuery($idOrderItemProcess = null, $idOrderItemState = null, $dateFilter = null)
    {
        $query = $this->salesOrderQuery;
        $query->addLastOrderGrandTotalToResult(static::FIELD_ORDER_GRAND_TOTAL);
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
        return $query->addItemStateNameAggregationToResult(static::FIELD_ITEM_STATE_NAMES_CSV);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $query
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addItemCount(SpySalesOrderQuery $query)
    {
        return $query->addItemCountToResult(static::FIELD_NUMBER_OF_ORDER_ITEMS);
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

        return $query
            ->filterByIdItemOrderProcess($idOrderItemProcess)
            ->filterByIdItemState($idOrderItemState);
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
            $query->filterByLastItemStateChange(new DateTime('-1 day'), Criteria::GREATER_THAN);
        } elseif ($dateFilter === self::DATE_FILTER_WEEK) {
            $query->filterByLastItemStateChange(
                [
                    'min' => new DateTime('-7 day'),
                    'max' => new DateTime('-1 day'),
                ],
                SprykerCriteria::BETWEEN
            );
        } else {
            $query->filterByLastItemStateChange(new DateTime('-7 day'), Criteria::LESS_THAN);
        }

        return $query;
    }
}
