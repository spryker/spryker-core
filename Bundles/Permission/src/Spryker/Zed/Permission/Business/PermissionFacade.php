<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class PermissionFacade implements PermissionFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll()
    {
        return new PermissionCollectionTransfer();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null)
    {
        //does the identifier contain permission key? (use a plugin from Company Role get this info)
        //get configuration by PermissionKey and Identifier (use same plugin from Company Role)
        //find the plugin in provided dependency
        //pass the configuration and the context to the plugin.

        return true;
    }
}
