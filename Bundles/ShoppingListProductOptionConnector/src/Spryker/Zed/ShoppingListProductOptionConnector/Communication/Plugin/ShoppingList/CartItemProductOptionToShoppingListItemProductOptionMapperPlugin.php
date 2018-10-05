<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemToShoppingListItemMapperPluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorFacadeInterface getFacade()
 */
class CartItemProductOptionToShoppingListItemProductOptionMapperPlugin extends AbstractPlugin implements ItemToShoppingListItemMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Maps ItemTransfer product options to ShoppingListItemTransfer product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function map(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFacade()
            ->mapCartItemProductOptionsToShoppingListItemProductOptions($itemTransfer, $shoppingListItemTransfer);
    }
}
