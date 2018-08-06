<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStoragePersistenceFactory getFactory()
 */
class ShoppingListStorageRepository extends AbstractRepository implements ShoppingListStorageRepositoryInterface
{
    /**
     * @param array $shippingListIds
     *
     * @return array
     */
    public function getCustomerReferencesByShippingListIds(array $shippingListIds): array
    {
        $query = $this->getFactory()
            ->createShippingListQuery()
            ->filterByIdShoppingList_In($shippingListIds);

        /** @var \Generated\Shared\Transfer\SpyShoppingListEntityTransfer[] $shoppingListEntityTransfers */
        $shoppingListEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        $customerReferences = [];
        foreach ($shoppingListEntityTransfers as $shoppingListEntityTransfer) {
            $customerReferences[] = $shoppingListEntityTransfer->getCustomerReference();
        }
        return $customerReferences;
    }
}
