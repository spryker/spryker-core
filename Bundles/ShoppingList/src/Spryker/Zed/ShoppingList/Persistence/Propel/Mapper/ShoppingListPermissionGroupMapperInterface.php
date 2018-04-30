<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;

interface ShoppingListPermissionGroupMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $permissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $permissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function mapShoppingListPermissionGroupTransfer(
        SpyShoppingListPermissionGroupEntityTransfer $permissionGroupEntityTransfer,
        ShoppingListPermissionGroupTransfer $permissionGroupTransfer
    ): ShoppingListPermissionGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $permissionGroupTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup $permissionGroupEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup
     */
    public function mapTransferToEntity(
        ShoppingListPermissionGroupTransfer $permissionGroupTransfer,
        SpyShoppingListPermissionGroup $permissionGroupEntity
    ): SpyShoppingListPermissionGroup;
}
