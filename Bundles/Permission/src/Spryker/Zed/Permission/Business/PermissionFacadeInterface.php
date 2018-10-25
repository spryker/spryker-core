<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

interface PermissionFacadeInterface
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
     * Specification:
     * - Checks that a permission is assigned to the identifier
     * - Finds a plugin by the permissionKey (if there is no enabled plugin return TRUE)
     * - If the plugin is not executable, returns TRUE
     * - Finds configuration by the permissionKey and the identifier
     * - Passes found configuration and the context to a permission plugin
     * - Returns the result of the permission execution
     *
     * @api
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null): bool;

    /**
     * Specification:
     * - Finds permission plugin stack registered in the permission Client dependency provider
     * - Adds permission plugin stack registered in the permission Zed dependency provider
     * - Adds new permission keys to permission table in a DB
     *
     * @api
     *
     * @return void
     */
    public function syncPermissionPlugins(): void;

    /**
     * Specification:
     * - Finds a permission by key
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    public function findPermissionByKey(string $key): ?PermissionTransfer;

    /**
     * Specification:
     * - Finds permissions registered either in Zed or Client dependency provider and removes non-infrastructural ones.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer;
}
