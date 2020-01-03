<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Dependency\Facade;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface OauthPermissionToPermissionFacadeInterface
{
    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsByIdentifier(string $identifier): PermissionCollectionTransfer;
}
