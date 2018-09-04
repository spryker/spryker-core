<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ShoppingListProductOption\ShoppingListProductOptionFactory getFactory()
 */
class ShoppingListItemProductOptionToItemProductOptionMapperPlugin extends AbstractPlugin implements ShoppingListItemToItemMapperPluginInterface
{
    /**
     * {@inheritdoc}
     * - Copies productOptions from ShoppingListItemTransfer to ItemTransfer.
     * - Merges the item to the item existing in cart if they have the same productOptions.
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
            ->getShoppingListItemToItemMapper()
            ->map($shoppingListItemTransfer, $itemTransfer);
    }
}
