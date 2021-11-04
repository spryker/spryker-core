<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface ShoppingListStorageRepositoryInterface
{
    /**
     * @param array<int> $shoppingListIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * @param array<string> $customerReference
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReferences(array $customerReference): ObjectCollection;

    /**
     * @param array<string> $customerReferences
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingList\Persistence\SpyShoppingList[]
     */
    public function findShoppingListEntitiesByCustomerReferences(array $customerReferences): ObjectCollection;

    /**
     * @param array $shoppingListCustomerStorageIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[]
     */
    public function findShoppingListCustomerStorageEntitiesByIds(array $shoppingListCustomerStorageIds): ObjectCollection;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $shoppingListCustomerStorageEntityIds
     *
     * @return array<\Generated\Shared\Transfer\SpyShoppingListCustomerStorageEntityTransfer>
     */
    public function findFilteredShoppingListCustomerStorageEntities(FilterTransfer $filterTransfer, array $shoppingListCustomerStorageEntityIds = []): array;
}
