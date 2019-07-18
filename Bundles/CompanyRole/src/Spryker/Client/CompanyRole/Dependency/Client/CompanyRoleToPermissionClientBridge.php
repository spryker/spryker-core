<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Dependency\Client;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class CompanyRoleToPermissionClientBridge implements CompanyRoleToPermissionClientInterface
{
    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     */
    public function __construct($permissionClient)
    {
        $this->permissionClient = $permissionClient;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer
    {
        return $this->permissionClient->findMergedRegisteredNonInfrastructuralPermissions();
    }
}
