<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListPersistenceFactory getFactory()
 */
interface ShoppingListRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findCustomerShoppingListByName(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findCustomerShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function findShoppingListPaginatedItems(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCustomerShoppingLists(string $customerReference): ShoppingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer;

    /**
     * @param int $idShoppingList
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findShoppingListItemsByIdShoppingList(int $idShoppingList): ShoppingListItemCollectionTransfer;

    /**
     * @param int[] $shoppingListIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findCustomerShoppingListsItemsByIds(array $shoppingListIds): ShoppingListItemCollectionTransfer;

    /**
     * @param int[] $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function findShoppingListItemsByIds(array $shoppingListItemIds): ShoppingListItemCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer;

    /**
     * @param int $idShoppingList
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function isShoppingListSharedWithCompanyBusinessUnit(int $idShoppingList, int $idCompanyBusinessUnit): bool;

    /**
     * @param int $idShoppingList
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isShoppingListSharedWithCompanyUser(int $idShoppingList, int $idCompanyUser): bool;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit[]
     */
    public function findCompanyBusinessUnitSharedShoppingListsIds(int $idCompanyBusinessUnit);

    /**
     * @param int $idCompanyBusinessUnit
     * @param string $shoppingListPermissionGroupName
     *
     * @return int[]
     */
    public function getCompanyBusinessUnitSharedShoppingListIdsByPermissionGroupName(int $idCompanyBusinessUnit, string $shoppingListPermissionGroupName): array;

    /**
     * @param int $idCompanyUser
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser[]
     */
    public function findCompanyUserSharedShoppingListsIds(int $idCompanyUser);

    /**
     * @param int $idCompanyUser
     * @param string $shoppingListPermissionGroupName
     *
     * @return int[]
     */
    public function getCompanyUserSharedShoppingListIdsByPermissionGroupName(int $idCompanyUser, string $shoppingListPermissionGroupName): array;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyUserSharedShoppingLists(int $idCompanyUser): ShoppingListCollectionTransfer;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function findCompanyBusinessUnitSharedShoppingLists(int $idCompanyBusinessUnit): ShoppingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer
     */
    public function getShoppingListCompanyBusinessUnitsByShoppingListId(ShoppingListTransfer $shoppingListTransfer): ShoppingListCompanyBusinessUnitCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer
     */
    public function getShoppingListCompanyUsersByShoppingListId(ShoppingListTransfer $shoppingListTransfer): ShoppingListCompanyUserCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer|null
     */
    public function findShoppingListCompanyUser(ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer): ?ShoppingListCompanyUserTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer|null
     */
    public function findShoppingListCompanyBusinessUnitBlackList(
        ShoppingListCompanyBusinessUnitBlacklistTransfer $shoppingListCompanyBusinessUnitBlacklistTransfer
    ): ?ShoppingListCompanyBusinessUnitBlacklistTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return int[]
     */
    public function getBlacklistedShoppingListIdsByIdCompanyUser(int $idCompanyUser): array;
}
