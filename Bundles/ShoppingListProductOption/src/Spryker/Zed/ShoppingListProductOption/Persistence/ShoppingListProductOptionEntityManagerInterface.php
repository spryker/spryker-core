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
     * @param int $idProductOption
     *
     * @return void
     */
    public function saveShoppingListItemProductOption(int $idShoppingListItem, int $idProductOption): void;

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void;
}
