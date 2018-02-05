<?php

namespace Spryker\Client\CompanyRole\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Permission\Communication\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\CompanyRole\CompanyRoleFactory getFactory()
 */
class PermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @return PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$customerTransfer) {
            return new PermissionCollectionTransfer();
        }

        return $customerTransfer->getPermissions();
    }
}