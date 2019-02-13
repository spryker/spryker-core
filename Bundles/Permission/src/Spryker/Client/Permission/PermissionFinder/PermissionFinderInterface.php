<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionFinder;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

interface PermissionFinderInterface
{
    /**
     * Specification:
     * - Finds a permission plugin by its key
     *
     * @param string $permissionKey
     *
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey);

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissionCollection(): PermissionCollectionTransfer;

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getCustomerPermissionsByKey(string $permissionKey): PermissionCollectionTransfer;

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    public function findCustomerPermissionByKey(string $permissionKey): ?PermissionTransfer;
}
