<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;

class FilterFieldChecker implements FilterFieldCheckerInterface
{
    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY
     */
    protected const FILTER_FIELD_TYPE_COMPANY = 'company';

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCompanyBusinessUnitFilterApplicable(array $filterFieldTransfers): bool
    {
        return $this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCompanyUserFilterApplicable(array $filterFieldTransfers): bool
    {
        if (
            !$this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            && !$this->isFilterFieldSet($filterFieldTransfers, static::FILTER_FIELD_TYPE_COMPANY)
        ) {
            return false;
        }

        return $this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param string $type
     *
     * @return bool
     */
    protected function isFilterFieldSet(array $filterFieldTransfers, string $type): bool
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === $type) {
                return true;
            }
        }

        return false;
    }
}
