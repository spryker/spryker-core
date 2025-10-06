<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Checker;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface EditCompanyOrdersPermissionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isEditCompanyOrderCartReorderAllowed(
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isEditCompanyOrderAllowed(CompanyUserTransfer $companyUserTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isOrderBelongsToCompany(?OrderTransfer $orderTransfer, CompanyUserTransfer $companyUserTransfer): bool;
}
