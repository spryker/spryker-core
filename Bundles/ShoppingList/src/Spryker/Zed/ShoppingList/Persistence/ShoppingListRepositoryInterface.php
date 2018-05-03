<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
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
     * @return null|\Generated\Shared\Transfer\ShoppingListTransfer
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
     * @return null|\Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function findShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer;

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
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer;

    /**
     * @param int $idShoppingList
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function isShoppingListSharedCompanyBusinessUnit(int $idShoppingList, int $idCompanyBusinessUnit): bool;

    /**
     * @param int $idShoppingList
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isShoppingListSharedCompanyUser(int $idShoppingList, int $idCompanyUser): bool;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCompanyBusinessUnitSharedShoppingListsIds(int $idCompanyBusinessUnit);

    /**
     * @param int $idCompanyUser
     *
     * @return mixed|\Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCompanyUserSharedShoppingListsIds(int $idCompanyUser);

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
}
