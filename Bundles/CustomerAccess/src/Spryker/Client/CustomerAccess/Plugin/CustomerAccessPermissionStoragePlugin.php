<?php

namespace Spryker\Client\CustomerAccess\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\CustomerAccess\CustomerAccessFactory getFactory()
 */
class CustomerAccessPermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer
    {
        $isCustomerLoggedIn = $this->getFactory()
            ->getCustomerClient()
            ->isLoggedIn();

        if ($isCustomerLoggedIn) {
            return $this->getFactory()->createCustomerAccess()->getLoggedInCustomerPermissions();
        }

        return $this->getFactory()->createCustomerAccess()->getLoggedOutCustomerPermissions();
    }
}
