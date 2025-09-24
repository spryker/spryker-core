<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission\Plugin\Permission;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\OauthPermission\OauthPermissionClientInterface getClient()
 * @method \Spryker\Client\OauthPermission\OauthPermissionFactory getFactory()
 */
class StoredOauthPermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * {@inheritDoc}
     *  - Reads permission collection from the storage using ids from token.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer
    {
        return $this->getFactory()->createPermissionReader()->getPermissions();
    }
}
