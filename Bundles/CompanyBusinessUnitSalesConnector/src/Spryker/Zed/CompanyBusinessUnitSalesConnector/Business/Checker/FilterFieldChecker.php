<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
use Generated\Shared\Transfer\FilterFieldCheckResponseTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface;

class FilterFieldChecker implements FilterFieldCheckerInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface
     */
    protected $companySalesConnectorFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface $companySalesConnectorFacade
     */
    public function __construct(CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface $companySalesConnectorFacade)
    {
        $this->companySalesConnectorFacade = $companySalesConnectorFacade;
    }

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
    public function isCustomerFilterApplicable(array $filterFieldTransfers): bool
    {
        if (!$this->isCompanyRelatedFiltersExist($filterFieldTransfers)) {
            return false;
        }

        return $this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCustomerSortingApplicable(array $filterFieldTransfers): bool
    {
        if (!$this->isCompanyRelatedFiltersExist($filterFieldTransfers)) {
            return false;
        }

        return $this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_ORDER_BY);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FilterFieldCheckResponseTransfer
     */
    public function isCompanyRelatedFiltersSet(
        FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
    ): FilterFieldCheckResponseTransfer {
        $filterFieldTransfers = $filterFieldCheckRequestTransfer->getFilterFields()->getArrayCopy();

        $isSuccessful = $this->isCompanyRelatedFiltersExist($filterFieldTransfers);

        return (new FilterFieldCheckResponseTransfer())->setIsSuccessful($isSuccessful);
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

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    protected function isCompanyRelatedFiltersExist(array $filterFieldTransfers): bool
    {
        return $this->companySalesConnectorFacade->isCompanyFilterApplicable($filterFieldTransfers)
            || $this->isFilterFieldSet($filterFieldTransfers, OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT);
    }
}
