<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Expander;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;

class OrderSearchQueryExpander implements OrderSearchQueryExpanderInterface
{
    public const FILTER_FIELD_TYPE_COMPANY = 'company';

    /**
     * @see Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    /**
     * @see SpySalesOrderTableMap::COL_COMPANY_UUID
     */
    protected const COLUMN_COMPANY_UUID = 'company_uuid';

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCompanyFilter(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $filterFieldTransfer = $this->extractCompanyBusinessUnitFilterField($filterFieldTransfers);

        if (!$filterFieldTransfer) {
            return $queryJoinCollectionTransfer;
        }

        return $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCompanyFilterQueryJoin($filterFieldTransfer->getValue())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return \Generated\Shared\Transfer\FilterFieldTransfer|null
     */
    protected function extractCompanyBusinessUnitFilterField(array $filterFieldTransfers): ?FilterFieldTransfer
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === static::FILTER_FIELD_TYPE_COMPANY) {
                return $filterFieldTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $companyUuid
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCompanyFilterQueryJoin(string $companyUuid): QueryJoinTransfer
    {
        $queryWhereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setColumn(static::COLUMN_COMPANY_UUID)
            ->setValue($companyUuid)
            ->setComparison(static::COMPARISON_EQUAL);

        return (new QueryJoinTransfer())->addQueryWhereCondition($queryWhereConditionTransfer);
    }
}
