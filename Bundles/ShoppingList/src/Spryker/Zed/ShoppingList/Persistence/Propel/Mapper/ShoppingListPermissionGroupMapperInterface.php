<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;

interface ShoppingListPermissionGroupMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer[] $shoppingListPermissionGroupEntityTransferCollection
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer $shoppingListPermissionGroupCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function mapShoppingListPermissionGroupEntitiesToShoppingListPermissionTransfers(
        array $shoppingListPermissionGroupEntityTransferCollection,
        ShoppingListPermissionGroupCollectionTransfer $shoppingListPermissionGroupCollectionTransfer
    ): ShoppingListPermissionGroupCollectionTransfer;
}
