<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\CompanyRole\CompanyRoleFactory getFactory()
 */
class PermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getPermissions()) {
            return new PermissionCollectionTransfer();
        }

        return $customerTransfer->getPermissions();
    }
}
