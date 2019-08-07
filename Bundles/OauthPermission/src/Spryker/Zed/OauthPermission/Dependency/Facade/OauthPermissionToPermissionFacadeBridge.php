<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Dependency\Facade;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class OauthPermissionToPermissionFacadeBridge implements OauthPermissionToPermissionFacadeInterface
{
    /**
     * @var \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\Permission\Business\PermissionFacadeInterface $permissionFacade
     */
    public function __construct($permissionFacade)
    {
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsByIdentifier(string $identifier): PermissionCollectionTransfer
    {
        return $this->permissionFacade->getPermissionsByIdentifier($identifier);
    }
}
