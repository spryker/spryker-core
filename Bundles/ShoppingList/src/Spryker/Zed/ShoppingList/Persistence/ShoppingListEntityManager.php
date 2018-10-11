<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitBlacklist;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem;
use Propel\Runtime\ActiveQuery\Criteria;
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
        $shoppingListEntity = $this->getFactory()
            ->createShoppingListQuery()
            ->filterByCustomerReference($shoppingListTransfer->getCustomerReference())
            ->filterByName($shoppingListTransfer->getName())
            ->findOne();

        $shoppingListEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListEntities = $this->getFactory()
            ->createShoppingListItemQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->find();
        foreach ($shoppingListEntities as $shoppingListEntity) {
            $shoppingListEntity->delete();
        }
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
            ->findOne();

        if ($shoppingListItemEntity !== null) {
            return $this->updateShoppingListItem($shoppingListItemTransfer, $shoppingListItemEntity);
        }

        return $this->createShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function createShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemEntity = $this->getFactory()
            ->createShoppingListItemMapper()
            ->mapTransferToEntity($shoppingListItemTransfer, new SpyShoppingListItem());

        $shoppingListItemEntity->save();
        $shoppingListItemTransfer->setIdShoppingListItem($shoppingListItemEntity->getIdShoppingListItem());

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem $shoppingListItemEntity
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function updateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        SpyShoppingListItem $shoppingListItemEntity
    ): ShoppingListItemTransfer {
        $shoppingListItemEntity = $this->getFactory()
            ->createShoppingListItemMapper()
            ->mapTransferToEntity($shoppingListItemTransfer, $shoppingListItemEntity);

        $shoppingListItemEntity->save();

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
            ->findOne()
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
        $shoppingListCompanyBusinessUnitEntity = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByIdShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit())
            ->findOne();

        if ($shoppingListCompanyBusinessUnitEntity !== null) {
            $this->updateShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer, $shoppingListCompanyBusinessUnitEntity);
            return;
        }

        $this->createShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return void
     */
    public function saveShoppingListCompanyUser(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
    ): void {
        $shoppingListCompanyUserEntity = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByIdShoppingListCompanyUser($shoppingListCompanyUserTransfer->getIdShoppingListCompanyUser())
            ->findOne();

        if ($shoppingListCompanyUserEntity !== null) {
            $this->updateShoppingListCompanyUser($shoppingListCompanyUserTransfer, $shoppingListCompanyUserEntity);
            return;
        }

        $this->createShoppingListCompanyUser($shoppingListCompanyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyUser(ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->findOneByIdShoppingListCompanyUser($shoppingListCompanyUserTransfer->getIdShoppingListCompanyUser())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyUsers(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListCompanyUserEntities = $this->getFactory()
            ->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->find();

        foreach ($shoppingListCompanyUserEntities as $shoppingListCompanyUserEntity) {
            $shoppingListCompanyUserEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListCompanyBusinessUnitEntities = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->find();

        foreach ($shoppingListCompanyBusinessUnitEntities as $shoppingListCompanyBusinessUnitEntity) {
            $shoppingListCompanyBusinessUnitEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     *
     * @return void
     */
    public function deleteShoppingListCompanyBusinessUnit(ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer): void
    {
        $this->getFactory()
            ->createShoppingListCompanyBusinessUnitQuery()
            ->findOneByIdShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     *
     * @return void
     */
    protected function createShoppingListCompanyBusinessUnit(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
    ): void {
        $shoppingListCompanyBusinessUnitEntity = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
                $shoppingListCompanyBusinessUnitTransfer,
                new SpyShoppingListCompanyBusinessUnit()
            );

        $shoppingListCompanyBusinessUnitEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
     *
     * @return void
     */
    protected function updateShoppingListCompanyBusinessUnit(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer,
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
    ): void {
        $shoppingListCompanyBusinessUnitEntity = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
                $shoppingListCompanyBusinessUnitTransfer,
                $shoppingListCompanyBusinessUnitEntity
            );

        $shoppingListCompanyBusinessUnitEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return void
     */
    protected function createShoppingListCompanyUser(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
    ): void {
        $shoppingListCompanyUserEntity = $this->getFactory()
            ->createShoppingListCompanyUserMapper()
            ->mapCompanyUserTransferToCompanyUserEntity(
                $shoppingListCompanyUserTransfer,
                new SpyShoppingListCompanyUser()
            );

        $shoppingListCompanyUserEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser $shoppingListCompanyUserEntity
     *
     * @return void
     */
    protected function updateShoppingListCompanyUser(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer,
        SpyShoppingListCompanyUser $shoppingListCompanyUserEntity
    ): void {
        $shoppingListCompanyUserEntity = $this->getFactory()
            ->createShoppingListCompanyUserMapper()
            ->mapCompanyUserTransferToCompanyUserEntity(
                $shoppingListCompanyUserTransfer,
                $shoppingListCompanyUserEntity
            );

        $shoppingListCompanyUserEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer
     *
     * @return void
     */
    public function createShoppingListCompanyBusinessUnitBlacklist(ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer): void
    {
        $shoppingListCompanyBusinessUnitBlacklistEntity = new SpyShoppingListCompanyBusinessUnitBlacklist();
        $shoppingListCompanyBusinessUnitBlacklistEntity->fromArray($shoppingListCompanyBusinessUnitBlacklistTransfer->modifiedToArray());
        $shoppingListCompanyBusinessUnitBlacklistEntity->save();
    }

    /**
     * @param int $idShoppingList
     *
     * @return void
     */
    public function deleteCompanyBusinessUnitBlacklistByShoppingListId(int $idShoppingList): void
    {
        $shoppingListCompanyBusinessUnitBlacklistEntities = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitBlacklistPropelQuery()
            ->useSpyShoppingListCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkShoppingList($idShoppingList)
            ->endUse()
            ->find();
        foreach ($shoppingListCompanyBusinessUnitBlacklistEntities as $shoppingListCompanyBusinessUnitBlacklistEntity) {
            $shoppingListCompanyBusinessUnitBlacklistEntity->delete();
        }
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deleteCompanyBusinessUnitBlacklistByBusinessUnitId(int $idCompanyBusinessUnit): void
    {
        $shoppingListCompanyBusinessUnitBlacklistEntities = $this->getFactory()
            ->createShoppingListCompanyBusinessUnitBlacklistPropelQuery()
            ->useSpyShoppingListCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->find();
        foreach ($shoppingListCompanyBusinessUnitBlacklistEntities as $shoppingListCompanyBusinessUnitBlacklistEntity) {
            $shoppingListCompanyBusinessUnitBlacklistEntity->delete();
        }
    }
}
