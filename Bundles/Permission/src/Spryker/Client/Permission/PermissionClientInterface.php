<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionClientInterface
{
    /**
     * Specification:
     * - Finds available permission list
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer;

    /**
     * Implements a general check for a chosen permission with the provided context
     *
     * Specification:
     * - Finds a permission key in a user session
     * - Finds a related to the permission key plugin
     * - If plugin is not found - return TRUE
     * - If the plugins is not executable - return true
     * - If the plugin is executable - returns boolean result of the execution
     *
     * @api
     *
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null): bool;

    /**
     * Specification:
     * - Finds permission plugin stack registered in the permission client dependency provider
     * - Returns a mapped permission collection transfer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissions(): PermissionCollectionTransfer;

    /**
     * Specification:
     * - Finds permission plugin stack registered either in Zed or Client dependency provider.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer;
}
