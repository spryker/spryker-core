<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Checker;

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
    public function checkOrderAccessByCustomerCompany(OrderTransfer $orderTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (!$this->hasCompany($orderTransfer, $customerTransfer)) {
            return false;
        }

        if ($orderTransfer->getCompanyUuid() !== $customerTransfer->getCompanyUserTransfer()->getCompany()->getUuid()) {
            return false;
        }

        return $this->can('SeeCompanyOrdersPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function hasCompany(OrderTransfer $orderTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (!$orderTransfer->getCompanyUuid()) {
            return false;
        }

        if (
            !$customerTransfer->getCompanyUserTransfer()
            || !$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
            || !$customerTransfer->getCompanyUserTransfer()->getCompany()
        ) {
            return false;
        }

        return true;
    }
}
