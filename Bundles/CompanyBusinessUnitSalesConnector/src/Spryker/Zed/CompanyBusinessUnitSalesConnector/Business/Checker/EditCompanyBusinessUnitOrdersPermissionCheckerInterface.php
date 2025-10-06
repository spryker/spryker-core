<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface EditCompanyBusinessUnitOrdersPermissionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isEditCompanyBusinessUnitOrderCartReorderAllowed(
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isEditCompanyBusinessUnitOrderAllowed(CompanyUserTransfer $companyUserTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isOrderBelongsToCompanyBusinessUnit(
        ?OrderTransfer $orderTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): bool;
}
