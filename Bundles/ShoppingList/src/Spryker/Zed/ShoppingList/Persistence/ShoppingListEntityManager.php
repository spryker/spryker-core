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
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
class ShoppingListEntityManager extends AbstractEntityManager implements ShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveShoppingList(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): SpyShoppingListEntityTransfer
    {
        return $this->save($shoppingListEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListByName(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $this->getFactory()
            ->createShoppingListQuery()
            ->filterByCustomerReference($shoppingListEntityTransfer->getCustomerReference())
            ->filterByName($shoppingListEntityTransfer->getName())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $shoppingListItems = $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
                ->filterByName($shoppingListEntityTransfer->getName())
                ->filterByCustomerReference($shoppingListEntityTransfer->getCustomerReference())
            ->endUse()
            ->find();

        foreach ($shoppingListItems as $shoppingListItem) {
            $shoppingListItem->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface|\Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer
     */
    public function saveShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): SpyShoppingListItemEntityTransfer
    {
        return $this->save($shoppingListItemEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListItem(SpyShoppingListItemEntityTransfer $shoppingListItemEntityTransfer): void
    {
        (new SpyShoppingListItem())->setIdShoppingListItem($shoppingListItemEntityTransfer->getIdShoppingListItem())->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer
     */
    public function saveShoppingListPermissionGroupEntity(SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer): SpyShoppingListPermissionGroupEntityTransfer
    {
        $shoppingListPermissionGroupEntity = $this->getFactory()
            ->createShoppingListPermissionGroupQuery()
            ->filterByName($shoppingListPermissionGroupEntityTransfer->getName())
            ->findOneOrCreate();

        $shoppingListPermissionGroupEntity->fromArray($shoppingListPermissionGroupEntityTransfer->modifiedToArray());
        $shoppingListPermissionGroupEntity->save();

        $shoppingListPermissionGroupEntityTransfer->fromArray($shoppingListPermissionGroupEntity->toArray(), true);

        return $shoppingListPermissionGroupEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer
    {
        $permissionEntity = $this->getFactory()
            ->createPermissionQuery()
            ->filterByKey($permissionEntityTransfer->getKey())
            ->findOneOrCreate();

        $permissionEntity->fromArray($permissionEntityTransfer->modifiedToArray());
        $permissionEntity->save();

        $permissionEntityTransfer->fromArray($permissionEntity->toArray(), true);

        return $permissionEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function saveShoppingListPermissionGroupToPermissionEntity(SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer, PermissionTransfer $permissionTransfer): void
    {
        $this->getFactory()
            ->createShoppingListPermissionGroupToPermissionQuery()
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->filterByFkShoppingListPermissionGroup($shoppingListPermissionGroupEntityTransfer->getIdShoppingListPermissionGroup())
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer $shoppingListCompanyBusinessUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveShoppingListCompanyBusinessUnitEntity(SpyShoppingListCompanyBusinessUnitEntityTransfer $shoppingListCompanyBusinessUnitEntityTransfer): SpyShoppingListCompanyBusinessUnitEntityTransfer
    {
        return $this->save($shoppingListCompanyBusinessUnitEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer $shoppingListCompanyUserEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveShoppingListCompanyUserEntity(SpyShoppingListCompanyUserEntityTransfer $shoppingListCompanyUserEntityTransfer): SpyShoppingListCompanyUserEntityTransfer
    {
        return $this->save($shoppingListCompanyUserEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyUsers(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($shoppingListEntityTransfer->getIdShoppingList())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyBusinessUnits(SpyShoppingListEntityTransfer $shoppingListEntityTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($shoppingListEntityTransfer->getIdShoppingList())
            ->delete();
    }
}
