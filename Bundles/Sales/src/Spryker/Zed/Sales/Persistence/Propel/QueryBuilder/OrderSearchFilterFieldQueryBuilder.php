<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\QueryBuilder;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class OrderSearchFilterFieldQueryBuilder implements OrderSearchFilterFieldQueryBuilderInterface
{
    /**
     * Used as a name for `all` search condition group binding to have a hook to extend and bind afterwards.
     */
    public const CONDITION_GROUP_ALL = 'CONDITION_GROUP_ALL';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     */
    public const SEARCH_TYPE_ALL = 'all';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     */
    protected const SEARCH_TYPE_ORDER_REFERENCE = 'orderReference';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     */
    protected const SEARCH_TYPE_ITEM_NAME = 'itemName';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     */
    protected const SEARCH_TYPE_ITEM_SKU = 'itemSku';

    protected const SEARCH_TYPE_DATE_FROM = 'dateFrom';
    protected const SEARCH_TYPE_DATE_TO = 'dateTo';

    protected const ORDER_SEARCH_TYPE_MAPPING = [
        self::SEARCH_TYPE_ORDER_REFERENCE => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        self::SEARCH_TYPE_ITEM_NAME => SpySalesOrderItemTableMap::COL_NAME,
        self::SEARCH_TYPE_ITEM_SKU => SpySalesOrderItemTableMap::COL_SKU,
    ];

    protected const ORDER_BY_COLUMN_MAPPING = [
        self::SEARCH_TYPE_ORDER_REFERENCE => SpySalesOrderTableMap::COL_ID_SALES_ORDER,
        'date' => SpySalesOrderTableMap::COL_CREATED_AT,
    ];

    protected const FILTER_FIELD_TYPE_CUSTOMER_REFERENCE = 'customerReference';
    protected const FILTER_FIELD_TYPE_ORDER_BY = 'orderBy';

    protected const DELIMITER_ORDER_BY = '::';

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function addSalesOrderQueryFilters(
        SpySalesOrderQuery $salesOrderQuery,
        OrderListTransfer $orderListTransfer
    ): SpySalesOrderQuery {
        foreach ($orderListTransfer->getFilterFields() as $filterFieldTransfer) {
            $salesOrderQuery = $this->addQueryFilter($salesOrderQuery, $filterFieldTransfer);
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addQueryFilter(
        SpySalesOrderQuery $salesOrderQuery,
        FilterFieldTransfer $filterFieldTransfer
    ): SpySalesOrderQuery {
            $filterFieldType = $filterFieldTransfer->getType();

        if ($filterFieldType === static::SEARCH_TYPE_DATE_FROM || $filterFieldType === static::SEARCH_TYPE_DATE_TO) {
            return $this->addDateFilter(
                $salesOrderQuery,
                $filterFieldTransfer
            );
        }

        if ($filterFieldType === static::SEARCH_TYPE_ALL || isset(static::ORDER_SEARCH_TYPE_MAPPING[$filterFieldType])) {
            return $this->addSearchTypeFilter(
                $salesOrderQuery,
                $filterFieldTransfer
            );
        }

        if ($filterFieldType === static::FILTER_FIELD_TYPE_ORDER_BY) {
            return $this->addOrderByFilter(
                $salesOrderQuery,
                $filterFieldTransfer
            );
        }

        if ($filterFieldType === static::FILTER_FIELD_TYPE_CUSTOMER_REFERENCE) {
            $salesOrderQuery->filterByCustomerReference($filterFieldTransfer->getValue());
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSearchTypeFilter(
        SpySalesOrderQuery $salesOrderQuery,
        FilterFieldTransfer $filterFieldTransfer
    ): SpySalesOrderQuery {
        $searchType = $filterFieldTransfer->getType();
        $searchValue = $filterFieldTransfer->getValue();

        if ($searchType === static::SEARCH_TYPE_ITEM_NAME || $searchType === static::SEARCH_TYPE_ITEM_SKU) {
            $salesOrderQuery->leftJoinItem();
        }

        if ($searchType !== static::SEARCH_TYPE_ALL && in_array($searchType, $this->getMappedSearchTypes())) {
            $salesOrderQuery->add(
                static::ORDER_SEARCH_TYPE_MAPPING[$searchType],
                $this->generateLikePattern($searchValue),
                Criteria::ILIKE
            );

            return $salesOrderQuery;
        }

        return $this->addAllSearchTypeFilter($salesOrderQuery, $filterFieldTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addAllSearchTypeFilter(
        SpySalesOrderQuery $salesOrderQuery,
        FilterFieldTransfer $filterFieldTransfer
    ): SpySalesOrderQuery {
        $conditions = [];
        $searchValue = $filterFieldTransfer->getValue();
        $salesOrderQuery->leftJoinItem();

        foreach ($this->getMappedSearchTypes() as $searchType) {
            $conditionName = uniqid('', true);

            $salesOrderQuery->addCond(
                $conditionName,
                static::ORDER_SEARCH_TYPE_MAPPING[$searchType],
                $this->generateLikePattern($searchValue),
                Criteria::ILIKE
            );

            $conditions[] = $conditionName;
        }

        $salesOrderQuery->combine(
            $conditions,
            Criteria::LOGICAL_OR,
            static::CONDITION_GROUP_ALL
        );

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addDateFilter(
        SpySalesOrderQuery $salesOrderQuery,
        FilterFieldTransfer $filterFieldTransfer
    ): SpySalesOrderQuery {
        $comparison = $filterFieldTransfer->getType() === static::SEARCH_TYPE_DATE_FROM ?
            Criteria::GREATER_EQUAL :
            Criteria::LESS_THAN;

        $salesOrderQuery->filterByCreatedAt($filterFieldTransfer->getValue(), $comparison);

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addOrderByFilter(
        SpySalesOrderQuery $salesOrderQuery,
        FilterFieldTransfer $filterFieldTransfer
    ): SpySalesOrderQuery {
        [$orderColumn, $orderDirection] = explode(static::DELIMITER_ORDER_BY, $filterFieldTransfer->getValue());

        $orderColumn = static::ORDER_BY_COLUMN_MAPPING[$orderColumn] ?? null;

        if ($orderColumn) {
            $salesOrderQuery->orderBy($orderColumn, $orderDirection);
        }

        return $salesOrderQuery;
    }

    /**
     * @return string[]
     */
    protected function getMappedSearchTypes(): array
    {
        return array_keys(static::ORDER_SEARCH_TYPE_MAPPING);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function generateLikePattern(string $value): string
    {
        return sprintf('%%%s%%', $value);
    }
}
