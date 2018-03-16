<?php

namespace Spryker\Client\CustomerAccess\CustomerAccess;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface CustomerAccessInterface
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedInCustomerPermissions(): PermissionCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedOutCustomerPermissions(): PermissionCollectionTransfer;
}