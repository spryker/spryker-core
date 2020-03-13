<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

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
    public function isCompanyUserEmailFilterApplicable(array $filterFieldTransfers): bool;
}
