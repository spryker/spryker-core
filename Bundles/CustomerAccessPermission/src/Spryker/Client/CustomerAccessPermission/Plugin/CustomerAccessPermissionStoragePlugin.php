<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionFactory getFactory()
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
