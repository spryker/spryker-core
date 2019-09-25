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
 * @method \Spryker\Zed\ShoppingListNote\Business\ShoppingListNoteFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListNote\ShoppingListNoteConfig getConfig()
 */
class ShoppingListItemNoteBeforeDeletePlugin extends AbstractPlugin implements ShoppingListItemBeforeDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes note from shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function execute(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $this->getFacade()->deleteShoppingListItemNote($shoppingListItemTransfer->getShoppingListItemNote());

        return $shoppingListItemTransfer;
    }
}
