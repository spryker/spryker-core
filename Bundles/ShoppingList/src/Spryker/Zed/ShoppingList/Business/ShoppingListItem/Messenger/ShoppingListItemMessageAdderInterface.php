<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger;

interface ShoppingListItemMessageAdderInterface
{
    /**
     * @return void
     */
    public function addShoppingListItemUpdateFailedMessage(): void;

    /**
     * @return void
     */
    public function addShoppingListItemUpdateSuccessMessage(): void;

    /**
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingFailedMessage(string $sku): void;

    /**
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingSuccessMessage(string $sku): void;

    /**
     * @return void
     */
    public function addShoppingListItemDeleteFailedMessage(): void;

    /**
     * @return void
     */
    public function addShoppingListItemDeleteSuccessMessage(): void;
}
