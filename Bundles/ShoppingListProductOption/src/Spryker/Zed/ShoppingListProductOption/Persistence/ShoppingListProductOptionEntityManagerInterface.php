<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Persistence;

interface ShoppingListProductOptionEntityManagerInterface
{
    /**
     * @param int $idShoppingListItem
     * @param int[] $idProductOptions
     *
     * @return void
     */
    public function saveShoppingListItemProductOptions(int $idShoppingListItem, array $idProductOptions): void;

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void;
}
