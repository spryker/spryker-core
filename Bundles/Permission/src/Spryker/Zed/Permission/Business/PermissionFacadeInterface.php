<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

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
     * - Checks that permission is assigned to identifier (plugin handler)
     * - Finds a plugin by the permissionKey (if there is no enabled plugin return TRUE)
     * - If the plugin is not executable, returns TRUE
     * - Finds configuration by the permissionKey and the identifier
     * - Passes the configuration and the context to the found plugin
     * - Returns the result of the plugin execution
     *
     * @api
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null);
}
