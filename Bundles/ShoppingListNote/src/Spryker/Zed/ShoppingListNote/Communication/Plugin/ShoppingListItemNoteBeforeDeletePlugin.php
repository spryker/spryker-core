<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Communication\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListNote\Business\ShoppingListNoteFacade getFacade()
 */
class ShoppingListItemNoteBeforeDeletePlugin extends AbstractPlugin implements ShoppingListItemBeforeDeletePluginInterface
{
    /**
     * {@inheritdoc}
     * - Removes note from shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function execute(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->getFacade()->deleteShoppingListItemNote($shoppingListItemTransfer->getShoppingListItemNote());
    }
}
