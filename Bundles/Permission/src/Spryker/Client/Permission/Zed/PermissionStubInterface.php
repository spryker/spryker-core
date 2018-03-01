<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\Zed;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionStubInterface
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer;
}
