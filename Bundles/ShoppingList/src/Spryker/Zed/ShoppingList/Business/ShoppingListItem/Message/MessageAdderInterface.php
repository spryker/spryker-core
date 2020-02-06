<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface MessageAdderInterface
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
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function addShoppingListItemAddingFailedMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function addShoppingListItemAddingSuccessMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void;

    /**
     * @return void
     */
    public function addShoppingListItemDeleteFailedMessage(): void;

    /**
     * @return void
     */
    public function addShoppingListItemDeleteSuccessMessage(): void;
}
