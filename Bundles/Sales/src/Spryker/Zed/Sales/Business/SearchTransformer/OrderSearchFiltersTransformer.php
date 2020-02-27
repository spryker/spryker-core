<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchTransformer;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;

class OrderSearchFiltersTransformer implements OrderSearchFiltersTransformerInterface
{
    protected const FILTER_FIELD_TYPE_ORDER_REFERENCE = 'orderReference';
    protected const FILTER_FIELD_TYPE_ITEM_NAME = 'itemName';
    protected const FILTER_FIELD_TYPE_ITEM_SKU = 'itemSku';
    protected const FILTER_FIELD_TYPE_ALL = 'all';
    protected const FILTER_FIELD_TYPE_DATE_FROM = 'dateFrom';
    protected const FILTER_FIELD_TYPE_DATE_TO = 'dateTo';
    protected const FILTER_FIELD_TYPE_ORDER_BY = 'orderBy';
    protected const FILTER_FIELD_TYPE_ORDER_DIRECTION = 'orderDirection';

    /**
     * @uses \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_CREATED_AT
     */
    protected const COLUMN_CREATED_AT = 'spy_sales_order.created_at';

    /**
     * @uses \Propel\Runtime\ActiveQuery\Criteria::GREATER_EQUAL
     */
    protected const COMPARISON_GREATER_EQUAL = '>=';

    /**
     * @uses \Propel\Runtime\ActiveQuery\Criteria::LESS_EQUAL
     */
    protected const COMPARISON_LESS_EQUAL = '<=';

    protected const ORDER_SEARCH_GROUP_MAPPING = [
        self::FILTER_FIELD_TYPE_ORDER_REFERENCE => 'spy_sales_order.order_reference',
        self::FILTER_FIELD_TYPE_ITEM_NAME => 'spy_sales_order_item.name',
        self::FILTER_FIELD_TYPE_ITEM_SKU => 'spy_sales_order_item.sku',
    ];

    protected const ORDER_BY_FIELDS_MAPPING = [
        'orderReference' => 'spy_sales_order.order_reference',
        'date' => 'spy_sales_order.created_at',
    ];

    protected const RELATION_ITEM = 'Item';

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function transformOrderSearchFilters(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        if ($orderListTransfer->getFilter()) {
            $orderListTransfer = $this->transformOrderByFilter($orderListTransfer);
        }

        $orderListTransfer = $this->transformFilterFieldsToQueryJoins($orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function transformOrderByFilter(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->requireFilter();

        $filterTransfer = $orderListTransfer->getFilter();
        $mappedOrderByColumn = static::ORDER_BY_FIELDS_MAPPING[$filterTransfer->getOrderBy()] ?? null;

        if ($mappedOrderByColumn) {
            $filterTransfer->setOrderBy($mappedOrderByColumn);
        }

        return $orderListTransfer->setFilter($filterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function transformFilterFieldsToQueryJoins(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();

        foreach ($orderListTransfer->getFilterFields() as $filterFieldTransfer) {
            $filterFieldType = $filterFieldTransfer->getType();

            if (
                $filterFieldType === static::FILTER_FIELD_TYPE_DATE_FROM
                || $filterFieldType === static::FILTER_FIELD_TYPE_DATE_TO
            ) {
                $queryJoinCollectionTransfer = $this->addDateQueryJoin(
                    $filterFieldTransfer,
                    $queryJoinCollectionTransfer
                );

                continue;
            }

            if (
                $filterFieldType === static::FILTER_FIELD_TYPE_ALL
                || isset(static::ORDER_SEARCH_GROUP_MAPPING[$filterFieldType])
            ) {
                $queryJoinCollectionTransfer = $this->addOrderSearchGroupQueryJoin(
                    $filterFieldTransfer,
                    $queryJoinCollectionTransfer
                );

                continue;
            }

            if (
                $filterFieldType === static::FILTER_FIELD_TYPE_ORDER_BY
                || $filterFieldType === static::FILTER_FIELD_TYPE_ORDER_DIRECTION
            ) {
                $orderListTransfer = $this->addOrderCondition($filterFieldTransfer, $orderListTransfer);
            }
        }

        return $orderListTransfer->setQueryJoins($queryJoinCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected function addOrderSearchGroupQueryJoin(
        FilterFieldTransfer $filterFieldTransfer,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $queryJoinTransfer = new QueryJoinTransfer();

        $searchGroupType = $filterFieldTransfer->getType();
        $searchGroupValue = $filterFieldTransfer->getValue();

        if ($searchGroupType === static::FILTER_FIELD_TYPE_ORDER_REFERENCE) {
            $queryJoinTransfer->addQueryWhereCondition(
                $this->createQueryWhereConditionTransfer($searchGroupType, $searchGroupValue)
            );

            return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
        }

        if (
            $searchGroupType === static::FILTER_FIELD_TYPE_ITEM_NAME
            || $searchGroupType === static::FILTER_FIELD_TYPE_ITEM_SKU
        ) {
            $queryJoinTransfer
                ->setRelation(static::RELATION_ITEM)
                ->addQueryWhereCondition(
                    $this->createQueryWhereConditionTransfer($searchGroupType, $searchGroupValue)
                );

            return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
        }

        return $this->addOrderSearchGroupAllQueryJoin($filterFieldTransfer, $queryJoinCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected function addDateQueryJoin(
        FilterFieldTransfer $filterFieldTransfer,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $comparison = $filterFieldTransfer->getType() === static::FILTER_FIELD_TYPE_DATE_FROM ?
            static::COMPARISON_GREATER_EQUAL :
            static::COMPARISON_LESS_EQUAL;

        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setColumn(static::COLUMN_CREATED_AT)
            ->setValue($filterFieldTransfer->getValue())
            ->setComparison($comparison);

        $queryJoinTransfer = (new QueryJoinTransfer())->addQueryWhereCondition($queryWhereConditionTransfer);

        return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected function addOrderSearchGroupAllQueryJoin(
        FilterFieldTransfer $filterFieldTransfer,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $queryJoinTransfer = new QueryJoinTransfer();

        foreach ($this->getMappedSearchGroups() as $searchGroupType) {
            $queryJoinTransfer->addQueryWhereCondition(
                $this->createQueryWhereConditionTransfer($searchGroupType, $filterFieldTransfer->getValue())
            );
        }

        $queryJoinTransfer->setRelation(static::RELATION_ITEM);

        return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function addOrderCondition(
        FilterFieldTransfer $filterFieldTransfer,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $orderListTransfer->requireFilter();

        if ($filterFieldTransfer->getType() === static::FILTER_FIELD_TYPE_ORDER_BY) {
            $orderListTransfer->getFilter()->setOrderBy($filterFieldTransfer->getValue());

            return $orderListTransfer;
        }

        $orderListTransfer->getFilter()->setOrderDirection($filterFieldTransfer->getValue());

        return $orderListTransfer;
    }

    /**
     * @param string $type
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\QueryWhereConditionTransfer
     */
    protected function createQueryWhereConditionTransfer(string $type, string $value): QueryWhereConditionTransfer
    {
        return (new QueryWhereConditionTransfer())
            ->setColumn(static::ORDER_SEARCH_GROUP_MAPPING[$type])
            ->setValue($value);
    }

    /**
     * @return string[]
     */
    protected function getMappedSearchGroups(): array
    {
        return array_keys(static::ORDER_SEARCH_GROUP_MAPPING);
    }
}
