<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;

class OrderSearchQueryExpander implements OrderSearchQueryExpanderInterface
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::CONDITION_GROUP_ALL
     */
    public const CONDITION_GROUP_ALL = 'CONDITION_GROUP_ALL';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ALL
     */
    public const FILTER_FIELD_TYPE_ALL = 'all';

    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    public const FILTER_FIELD_TYPE_ORDER_BY = 'orderBy';

    public const MAPPED_ORDER_BY_FILTERS = [
        'customerName' => self::COLUMN_FULL_NAME,
        'customerEmail' => self::COLUMN_EMAIL,
    ];

    protected const COLUMN_FULL_NAME = 'full_name';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_FIRST_NAME
     */
    protected const COLUMN_FIRST_NAME = 'first_name';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_FIRST_NAME
     */
    protected const COLUMN_LAST_NAME = 'last_name';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_COMPANY_BUSINESS_UNIT_UUID
     */
    protected const COLUMN_COMPANY_BUSINESS_UNIT_UUID = 'company_business_unit_uuid';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_EMAIL
     */
    protected const COLUMN_EMAIL = 'email';

    /**
     * @see \Propel\Runtime\ActiveQuery\Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::DELIMITER_ORDER_BY
     */
    protected const DELIMITER_ORDER_BY = '::';

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCompanyBusinessUnitFilter(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $filterFieldTransfer = $this->extractFilterFieldByType(
            $filterFieldTransfers,
            static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT
        );

        if (!$filterFieldTransfer) {
            return $queryJoinCollectionTransfer;
        }

        return $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCompanyBusinessUnitFilterQueryJoin($filterFieldTransfer->getValue())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCustomerFilter(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $filterFieldTransfer = $this->extractFilterFieldByType(
            $filterFieldTransfers,
            static::FILTER_FIELD_TYPE_ALL
        );

        if (!$filterFieldTransfer) {
            return $queryJoinCollectionTransfer;
        }

        $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCustomerEmailFilterQueryJoin($filterFieldTransfer->getValue())
        );

        $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCustomerNameFilterQueryJoin($filterFieldTransfer->getValue())
        );

        return $queryJoinCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCustomerSorting(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $filterFieldTransfer = $this->extractFilterFieldByType(
            $filterFieldTransfers,
            static::FILTER_FIELD_TYPE_ORDER_BY
        );

        if (!$filterFieldTransfer) {
            return $queryJoinCollectionTransfer;
        }

        $mappedOrderByFilters = array_keys(static::MAPPED_ORDER_BY_FILTERS);
        [$orderColumn, $orderDirection] = explode(static::DELIMITER_ORDER_BY, $filterFieldTransfer->getValue());

        if (!in_array($orderColumn, $mappedOrderByFilters, true) || !$orderDirection) {
            return $queryJoinCollectionTransfer;
        }

        return $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCustomerSortingQueryJoin($orderColumn, $orderDirection)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\FilterFieldTransfer|null
     */
    protected function extractFilterFieldByType(array $filterFieldTransfers, string $type): ?FilterFieldTransfer
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === $type) {
                return $filterFieldTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $companyBusinessUnitUuid
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCompanyBusinessUnitFilterQueryJoin(string $companyBusinessUnitUuid): QueryJoinTransfer
    {
        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setColumn(static::COLUMN_COMPANY_BUSINESS_UNIT_UUID)
            ->setValue($companyBusinessUnitUuid)
            ->setComparison(static::COMPARISON_EQUAL);

        return (new QueryJoinTransfer())->addQueryWhereCondition($queryWhereConditionTransfer);
    }

    /**
     * @param string $searchString
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCustomerEmailFilterQueryJoin(string $searchString): QueryJoinTransfer
    {
        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setMergeWithCondition(static::CONDITION_GROUP_ALL)
            ->setColumn(static::COLUMN_EMAIL)
            ->setValue($searchString);

        return (new QueryJoinTransfer())->addQueryWhereCondition($queryWhereConditionTransfer);
    }

    /**
     * @param string $searchString
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCustomerNameFilterQueryJoin(string $searchString): QueryJoinTransfer
    {
        $fullNameColumn = $this->getConcatenatedFullNameColumn();

        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setMergeWithCondition(static::CONDITION_GROUP_ALL)
            ->setColumn($fullNameColumn)
            ->setValue($searchString);

        return (new QueryJoinTransfer())->setWithColumns([static::COLUMN_FULL_NAME => $fullNameColumn])
            ->addQueryWhereCondition($queryWhereConditionTransfer);
    }

    /**
     * @param string $orderBy
     * @param string $orderDirection
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCustomerSortingQueryJoin(string $orderBy, string $orderDirection): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setWithColumns([static::COLUMN_FULL_NAME => $this->getConcatenatedFullNameColumn()])
            ->setOrderBy(static::MAPPED_ORDER_BY_FILTERS[$orderBy])
            ->setOrderDirection($orderDirection);
    }

    /**
     * @return string
     */
    protected function getConcatenatedFullNameColumn(): string
    {
        return sprintf('CONCAT(%s,\' \', %s)', static::COLUMN_FIRST_NAME, static::COLUMN_LAST_NAME);
    }
}
