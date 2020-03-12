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
    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @see Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    /**
     * @see SpySalesOrderTableMap::COL_COMPANY_BUSINESS_UNIT_UUID
     */
    protected const COLUMN_COMPANY_BUSINESS_UNIT_UUID = 'company_business_unit_uuid';

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
        $filterFieldTransfer = $this->extractCompanyBusinessUnitFilterField($filterFieldTransfers);

        if (!$filterFieldTransfer) {
            return $queryJoinCollectionTransfer;
        }

        return $queryJoinCollectionTransfer->addQueryJoin(
            $this->createCompanyBusinessUnitFilterQueryJoin($filterFieldTransfer->getValue())
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
            if ($filterFieldTransfer->getType() === static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT) {
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
}
