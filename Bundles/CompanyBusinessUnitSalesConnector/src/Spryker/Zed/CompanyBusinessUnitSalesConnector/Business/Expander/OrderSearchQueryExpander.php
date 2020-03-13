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
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder;

class OrderSearchQueryExpander implements OrderSearchQueryExpanderInterface
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ALL
     */
    public const FILTER_FIELD_TYPE_ALL = 'all';
    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @see \Propel\Runtime\ActiveQuery\Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_COMPANY_BUSINESS_UNIT_UUID
     */
    protected const COLUMN_COMPANY_BUSINESS_UNIT_UUID = 'company_business_unit_uuid';

    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_EMAIL
     */
    protected const COLUMN_EMAIL = 'email';

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
    public function expandQueryJoinCollectionWithCompanyUserEmailFilter(
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

        return $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCompanyUserEmailFilterQueryJoin($filterFieldTransfer->getValue())
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
    protected function createCompanyUserEmailFilterQueryJoin(string $searchString): QueryJoinTransfer
    {
        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setMergeWithCondition(OrderSearchFilterFieldQueryBuilder::CONDITION_GROUP_ALL)
            ->setColumn(static::COLUMN_EMAIL)
            ->setValue($searchString);

        return (new QueryJoinTransfer())->addQueryWhereCondition($queryWhereConditionTransfer);
    }
}
