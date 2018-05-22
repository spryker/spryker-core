<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\CustomerExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer->requireCompanyUserTransfer();

        $permissionCollectionTransfer = $this->sharedCartRepository
            ->findPermissionsByIdCompanyUser(
                $customerTransfer
                    ->getCompanyUserTransfer()
                    ->getIdCompanyUser()
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
