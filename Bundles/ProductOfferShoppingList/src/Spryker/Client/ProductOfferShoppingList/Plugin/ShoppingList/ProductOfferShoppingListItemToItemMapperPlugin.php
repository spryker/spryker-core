<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferShoppingList\ProductOfferShoppingListFactory getFactory()
 */
class ProductOfferShoppingListItemToItemMapperPlugin extends AbstractPlugin implements ShoppingListItemToItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ShoppingListItemTransfer` transfer properties to `ItemTransfer` transfer properties.
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
            ->createProductOfferShoppingListItemToItemMapper()
            ->map($shoppingListItemTransfer, $itemTransfer);
    }
}
