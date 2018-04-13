<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;

interface ShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListEntityTransfer
     */
    public function saveShoppingList(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): SpyShoppingListEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListByName(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer
     */
    public function saveShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): SpyShoppingListItemEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer
     */
    public function saveShoppingListPermissionGroupEntity(SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer): SpyShoppingListPermissionGroupEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function saveShoppingListPermissionGroupToPermissionEntity(SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer, PermissionTransfer $permissionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer $shoppingListCompanyBusinessUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer
     */
    public function saveShoppingListCompanyBusinessUnitEntity(SpyShoppingListCompanyBusinessUnitEntityTransfer $shoppingListCompanyBusinessUnitEntityTransfer): SpyShoppingListCompanyBusinessUnitEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer $shoppingListCompanyUserEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer
     */
    public function saveShoppingListCompanyUserEntity(SpyShoppingListCompanyUserEntityTransfer $shoppingListCompanyUserEntityTransfer): SpyShoppingListCompanyUserEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyUsers(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyBusinessUnits(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void;
}
