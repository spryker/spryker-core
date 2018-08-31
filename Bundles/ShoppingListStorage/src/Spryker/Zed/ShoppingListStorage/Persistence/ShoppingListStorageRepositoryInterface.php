<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Propel\Runtime\Collection\ObjectCollection;

interface ShoppingListStorageRepositoryInterface
{
    /**
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getOwnCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getSharedWithCompanyUserCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getSharedWithCompanyBusinessUnitCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return int[]
     */
    public function getShoppingListIdsByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyUserIds
     *
     * @return int[]
     */
    public function getShoppingListIdsByCompanyUserIds(array $companyUserIds): array;

    /**
     * @param string[] $customerReference
     *
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReferences(array $customerReference): ObjectCollection;

    /**
     * @param string[] $customerReferences
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingList[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListEntitiesByCustomerReferences(array $customerReferences): ObjectCollection;

    /**
     * @param array $shoppingListCustomerStorageIds
     *
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findShoppingListCustomerStorageEntitiesByIds(array $shoppingListCustomerStorageIds): ObjectCollection;
}
