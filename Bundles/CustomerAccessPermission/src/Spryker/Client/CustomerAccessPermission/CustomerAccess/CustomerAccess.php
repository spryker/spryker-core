<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\CustomerAccess;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig;
use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerAccessStorageClientInterface;

class CustomerAccess implements CustomerAccessInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerAccessStorageClientInterface
     */
    protected $customerAccessStorageReader;

    /**
     * @var \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig
     */
    protected $customerAccessConfig;

    /**
     * @param \Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerAccessStorageClientInterface $customerAccessStorageReader
     * @param \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig $customerAccessConfig
     */
    public function __construct(CustomerAccessPermissionToCustomerAccessStorageClientInterface $customerAccessStorageReader, CustomerAccessPermissionConfig $customerAccessConfig)
    {
        $this->customerAccessStorageReader = $customerAccessStorageReader;
        $this->customerAccessConfig = $customerAccessConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedInCustomerPermissions(): PermissionCollectionTransfer
    {
        $authenticatedCustomerAccess = $this->customerAccessStorageReader->getAuthenticatedCustomerAccess();
        return $this->getPermissionsFromCustomerAccess($authenticatedCustomerAccess);
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedOutCustomerPermissions(): PermissionCollectionTransfer
    {
        $unauthenticatedCustomerAccess = $this->customerAccessStorageReader->getUnauthenticatedCustomerAccess();
        return $this->getPermissionsFromCustomerAccess($unauthenticatedCustomerAccess);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function getPermissionsFromCustomerAccess(CustomerAccessTransfer $customerAccessTransfer)
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            if ($this->customerAccessConfig->hasPluginToSeeContentType($contentTypeAccess->getContentType())) {
                $permission = new PermissionTransfer();
                $permission->setKey(
                    $this->customerAccessConfig->getPluginNameToSeeContentType($contentTypeAccess->getContentType())
                );

                $permissionCollectionTransfer->addPermission($permission);
            }
        }

        return $permissionCollectionTransfer;
    }
}
