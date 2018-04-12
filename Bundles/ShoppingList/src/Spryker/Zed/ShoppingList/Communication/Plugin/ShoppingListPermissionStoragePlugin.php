<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Communication\Plugin;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingList\Communication\ShoppingListCommunicationFactory getFactory()
 */
class ShoppingListPermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @param int|string $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection($idCompanyUser): PermissionCollectionTransfer
    {
        return $this->getFacade()->findCompanyUserPermissions($idCompanyUser);
    }
}
