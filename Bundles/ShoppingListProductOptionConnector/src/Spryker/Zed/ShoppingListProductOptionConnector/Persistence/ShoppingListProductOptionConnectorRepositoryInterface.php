<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer;

interface ShoppingListProductOptionConnectorRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return int[]
     */
    public function getShoppingListItemProductOptionIdsByIdShoppingListItem(int $idShoppingListItem): array;

    /**
     * @param int[] $shoppingListItemIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer
     */
    public function getShoppingListProductOptionCollectionByShoppingListItemIds(array $shoppingListItemIds): ShoppingListProductOptionCollectionTransfer;
}
