<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\Collection\Collection;

interface ShoppingListStorageRepositoryInterface
{
    /**
     * @param array<int> $shoppingListIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * @param array<string> $customerReferences
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage>
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReferences(array $customerReferences): Collection;

    /**
     * @param array<string> $customerReferences
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ShoppingList\Persistence\SpyShoppingList>
     */
    public function findShoppingListEntitiesByCustomerReferences(array $customerReferences): Collection;

    /**
     * @param array $shoppingListCustomerStorageIds
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage>
     */
    public function findShoppingListCustomerStorageEntitiesByIds(array $shoppingListCustomerStorageIds): Collection;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $shoppingListCustomerStorageEntityIds
     *
     * @return array<\Generated\Shared\Transfer\SpyShoppingListCustomerStorageEntityTransfer>
     */
    public function findFilteredShoppingListCustomerStorageEntities(FilterTransfer $filterTransfer, array $shoppingListCustomerStorageEntityIds = []): array;
}
