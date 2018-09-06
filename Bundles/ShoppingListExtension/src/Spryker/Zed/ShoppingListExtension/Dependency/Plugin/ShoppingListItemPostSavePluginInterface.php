<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListItemPostSavePluginInterface
{
    /**
     * Specification:
     * - This plugin runs after shopping list item is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function postSave(ShoppingListItemTransfer $shoppingListItemTransfer): void;
}
