<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class PermissionChecker implements PermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function checkSeeBusinessUnitOrdersPermission(OrderTransfer $orderTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (
            !$orderTransfer->getCompanyBusinessUnitUuid()
            || !$customerTransfer->getCompanyUserTransfer()
            || !$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
            || !$customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()
        ) {
            return false;
        }

        if ($orderTransfer->getCompanyBusinessUnitUuid() !== $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getUuid()) {
            return false;
        }

        return $this->can('SeeBusinessUnitOrdersPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());
    }
}
