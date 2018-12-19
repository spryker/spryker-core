<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\CustomerAccess;

use Generated\Shared\Transfer\CustomerAccessTransfer;
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

    /**
     * @return string
     */
    public function getCustomerSecuredPatternForUnauthenticatedCustomerAccess(): string;

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     * @param string $customerSecuredPattern
     *
     * @return string
     */
    public function applyCustomerAccessOnCustomerSecuredPattern(
        CustomerAccessTransfer $customerAccessTransfer,
        string $customerSecuredPattern
    ): string;
}
