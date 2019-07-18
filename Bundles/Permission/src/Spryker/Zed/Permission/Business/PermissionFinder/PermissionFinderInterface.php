<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionFinderInterface
{
    /**
     * Specification:
     * - Finds permission plugins in the permission plugin stack
     * - Provides its instance
     * - Currently permission plugins are state-full by creation (created once)
     *
     * @param string $permissionKey
     *
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey);

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissions(): PermissionCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer;

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsByIdentifier(string $identifier): PermissionCollectionTransfer;
}
