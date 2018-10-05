<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorFactory getFactory()
 */
class ShoppingListItemProductOptionToItemProductOptionMapperPlugin extends AbstractPlugin implements ShoppingListItemToItemMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Copies product options from ShoppingListItemTransfer to ItemTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function map(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createShoppingListItemToItemMapper()
            ->map($shoppingListItemTransfer, $itemTransfer);
    }
}
