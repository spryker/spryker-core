<?php

namespace Spryker\Zed\CompanyRole\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class PermissionCustomerExpanderPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->requireCompanyUserTransfer();

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