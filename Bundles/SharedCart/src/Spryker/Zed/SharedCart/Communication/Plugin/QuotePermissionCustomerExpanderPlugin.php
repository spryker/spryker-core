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
        // TODO: Zed plugins shouldn't contain business logic. You need to move this behind the facade of the module. Please also check other modules where you added plugins. (Be pragmatic, plugins which contains a couple of lines can be kept, i.e. permission plugins, etc.)
        if ($customerTransfer->getCompanyUserTransfer()) {
            $permissionCollectionTransfer = $this->getFacade()
                ->findPermissionsByIdCompanyUser(
                    $customerTransfer
                        ->getCompanyUserTransfer()
                        ->getIdCompanyUser()
                );

            return $this->addCustomerPermissions($customerTransfer, $permissionCollectionTransfer);
        }

        $permissionCollectionTransfer = $this->getFacade()
            ->findPermissionsByCustomer(
                $customerTransfer
                    ->getCustomerReference()
            );

        return $this->addCustomerPermissions($customerTransfer, $permissionCollectionTransfer);
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
