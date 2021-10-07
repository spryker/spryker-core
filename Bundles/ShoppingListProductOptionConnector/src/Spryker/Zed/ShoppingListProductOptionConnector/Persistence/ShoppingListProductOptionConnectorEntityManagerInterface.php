<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

use ArrayObject;

interface ShoppingListProductOptionConnectorEntityManagerInterface
{
    /**
     * @param int $idShoppingListItem
     * @param int $idProductOption
     *
     * @return void
     */
    public function saveShoppingListItemProductOption(int $idShoppingListItem, int $idProductOption): void;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShoppingListItemTransfer> $shoppingListItemTransfers
     *
     * @return void
     */
    public function saveShoppingListItemProductOptionInBulk(ArrayObject $shoppingListItemTransfers): void;

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void;

    /**
     * @param array<int> $shoppingListItemIds
     *
     * @return void
     */
    public function removeShoppingListItemProductOptionsByShoppingListItemIds(array $shoppingListItemIds): void;

    /**
     * @param array<int> $productOptionValueIds
     *
     * @return void
     */
    public function removeShoppingListItemProductOptionsByProductOptionValueIds(array $productOptionValueIds): void;
}
