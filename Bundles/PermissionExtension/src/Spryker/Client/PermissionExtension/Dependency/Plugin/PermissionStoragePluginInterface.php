<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PermissionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionStoragePluginInterface
{
    /**
     * Specification:
     * - Finds permissions in a user session
     * - Populates them in a permission collection with configurations
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer;
}
