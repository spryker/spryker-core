<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOptionFacadeInterface getFacade()
 */
class ShoppingListItemProductOptionExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     *
     * {@inheritdoc}
     * - Adds a product options to shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFacade()
            ->expandItem($shoppingListItemTransfer);
    }
}
