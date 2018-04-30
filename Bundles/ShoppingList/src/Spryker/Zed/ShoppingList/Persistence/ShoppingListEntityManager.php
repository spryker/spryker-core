<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
class ShoppingListEntityManager extends AbstractEntityManager implements ShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function saveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListEntity = $this->getFactory()
            ->createShoppingListQuery()
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->findOneOrCreate();
        $shoppingListEntity = $this->getFactory()->createShoppingListMapper()
            ->mapTransferToEntity($shoppingListTransfer, $shoppingListEntity);

        $shoppingListEntity->save();
        $shoppingListTransfer->setIdShoppingList($shoppingListEntity->getIdShoppingList());

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListByName(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getFactory()
            ->createShoppingListQuery()
            ->filterByCustomerReference($shoppingListTransfer->getCustomerReference())
            ->filterByName($shoppingListTransfer->getName())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getFactory()
            ->createShoppingListItemQuery()
            ->useSpyShoppingListQuery()
            ->filterByName($shoppingListTransfer->getName())
            ->filterByCustomerReference($shoppingListTransfer->getCustomerReference())
            ->endUse()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemEntity = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem())
            ->findOneOrCreate();
        $shoppingListEntity = $this->getFactory()->createShoppingListItemMapper()
            ->mapTransferToEntity($shoppingListItemTransfer, $shoppingListItemEntity);

        $shoppingListEntity->save();
        $shoppingListItemTransfer->setIdShoppingListItem($shoppingListEntity->getIdShoppingListItem());

        return $shoppingListItemTransfer;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function deleteShoppingListItem(int $idShoppingListItem): void
    {
        $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByIdShoppingListItem($idShoppingListItem)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer
     */
    public function saveShoppingListPermissionGroup(
        SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
    ): SpyShoppingListPermissionGroupEntityTransfer {
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
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function saveShoppingListPermissionGroupToPermission(
        SpyShoppingListPermissionGroupEntityTransfer $shoppingListPermissionGroupEntityTransfer,
        PermissionTransfer $permissionTransfer
    ): void {
        $this->getFactory()
            ->createShoppingListPermissionGroupToPermissionQuery()
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->filterByFkShoppingListPermissionGroup($shoppingListPermissionGroupEntityTransfer->getIdShoppingListPermissionGroup())
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     *
     * @return void
     */
    public function saveShoppingListCompanyBusinessUnit(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
    ): void {
        $shoppingListCompanyBusinessUnitEntityTransfer = (new SpyShoppingListCompanyBusinessUnitEntityTransfer())
            ->setFkCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setFkShoppingList($shoppingListCompanyBusinessUnitTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup());

        $this->save($shoppingListCompanyBusinessUnitEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return void
     */
    public function saveShoppingListCompanyUser(ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer): void
    {
        $shoppingListCompanyUserEntityTransfer = (new SpyShoppingListCompanyUserEntityTransfer())
            ->setFkCompanyUser($shoppingListCompanyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListCompanyUserTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup());

        $this->save($shoppingListCompanyUserEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyUsers(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->delete();
    }
}
