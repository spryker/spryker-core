<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

class OffersTableQueryBuilder implements OffersTableQueryBuilderInterface
{
    const FIELD_ITEM_STATE_NAMES_CSV = 'item_state_names_csv';
    const FIELD_NUMBER_OF_ORDER_ITEMS = 'number_of_order_items';
    const DATE_FILTER_DAY = 'day';
    const DATE_FILTER_WEEK = 'week';
    const FIELD_ORDER_GRAND_TOTAL = 'order_grand_total';

    /**
     * @use \Spryker\Zed\Offer\OfferConfig::getOrderTypeOffer()
     */
    public const ORDER_TYPE_OFFER = 'offer';

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected $salesOrderQuery;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     */
    public function __construct(
        SpySalesOrderQuery $salesOrderQuery
    ){
        $this->salesOrderQuery = $salesOrderQuery;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function buildQuery()
    {
        $query = $this->salesOrderQuery;

        $query->filterByType(static::ORDER_TYPE_OFFER);
        $query->addLastOrderGrandTotalToResult(static::FIELD_ORDER_GRAND_TOTAL);

        $query = $this->addItemStates($query);
        $query = $this->addItemCount($query);

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
}
