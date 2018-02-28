<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function upsertPermissionCollection(PermissionCollectionTransfer $permissionCollectionTransfer): PermissionCollectionTransfer;
}
