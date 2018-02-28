<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class PermissionCustomerExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if (!$customerTransfer->getCompanyUserTransfer()) {
            return $customerTransfer;
        }

        $permissionCollectionTransfer = $this->getFacade()
            ->findPermissionsByIdCompanyUser(
                $customerTransfer
                    ->getCompanyUserTransfer()
                    ->getIdCompanyUser()
            );

        $customerTransfer->setPermissions($permissionCollectionTransfer);

        return $customerTransfer;
    }
}
