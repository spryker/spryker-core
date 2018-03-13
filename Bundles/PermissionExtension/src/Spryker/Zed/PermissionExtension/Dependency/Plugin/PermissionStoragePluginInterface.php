<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PermissionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionStoragePluginInterface
{
    /**
     * Specification:
     * - Finds permission in a database for a specific user
     * - Populates them into a permission collection with configurations
     *
     * @api
     *
     * @param int|string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection($identifier): PermissionCollectionTransfer;
}
