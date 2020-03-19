<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
use Generated\Shared\Transfer\FilterFieldCheckResponseTransfer;

interface FilterFieldCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCompanyBusinessUnitFilterApplicable(array $filterFieldTransfers): bool;

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCustomerFilterApplicable(array $filterFieldTransfers): bool;

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCustomerSortingApplicable(array $filterFieldTransfers): bool;

    /**
     * @param \Generated\Shared\Transfer\FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FilterFieldCheckResponseTransfer
     */
    public function isCompanyRelatedFiltersSet(
        FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
    ): FilterFieldCheckResponseTransfer;
}
