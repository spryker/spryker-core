<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger;

interface ShoppingListItemMessageAdderInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingSuccessMessage(string $sku): void;

    /**
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingFailedMessage(string $sku): void;
}
