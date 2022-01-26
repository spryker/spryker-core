<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemToShoppingListItemMapperPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShoppingList\Business\ProductOfferShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShoppingList\ProductOfferShoppingListConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShoppingList\Communication\ProductOfferShoppingListCommunicationFactory getFactory()
 */
class ProductOfferItemToShoppingListItemMapperPlugin extends AbstractPlugin implements ItemToShoppingListItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ItemTransfer.productOfferReference` transfer property to `ShoppingListItemTransfer.productOfferReference` transfer property.
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
        return $shoppingListItemTransfer->setProductOfferReference($itemTransfer->getProductOfferReference());
    }
}
