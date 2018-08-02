<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;

interface ShoppingListPermissionGroupMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer[] $permissionGroupEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function mapPermissionGroupCollectionTransfer(array $permissionGroupEntityTransferCollection): ShoppingListPermissionGroupCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $permissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function mapPermissionGroupTransfer(
        SpyShoppingListPermissionGroupEntityTransfer $permissionGroupEntityTransfer,
        ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
    ): ShoppingListPermissionGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup $shoppingListPermissionGroupEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup
     */
    public function mapTransferToEntity(
        ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer,
        SpyShoppingListPermissionGroup $shoppingListPermissionGroupEntity
    ): SpyShoppingListPermissionGroup;
}
