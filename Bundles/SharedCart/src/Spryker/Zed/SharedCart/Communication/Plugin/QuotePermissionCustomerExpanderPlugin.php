<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class QuotePermissionCustomerExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            $permissionCollectionTransfer = $this->getFacade()
                ->findPermissionsByIdCompanyUser(
                    $customerTransfer
                        ->getCompanyUserTransfer()
                        ->getIdCompanyUser()
                );

            return $this->addCustomerPermissions($customerTransfer, $permissionCollectionTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function addCustomerPermissions(
        CustomerTransfer $customerTransfer,
        PermissionCollectionTransfer $permissionCollectionTransfer
    ): CustomerTransfer {
        if (!$customerTransfer->getPermissions()) {
            $customerTransfer->setPermissions($permissionCollectionTransfer);

            return $customerTransfer;
        }

        $customerPermissionCollectionTransfer = $customerTransfer->getPermissions();
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            $customerPermissionCollectionTransfer->addPermission($permissionTransfer);
        }
        $customerTransfer->setPermissions($customerPermissionCollectionTransfer);

        return $customerTransfer;
    }
}
