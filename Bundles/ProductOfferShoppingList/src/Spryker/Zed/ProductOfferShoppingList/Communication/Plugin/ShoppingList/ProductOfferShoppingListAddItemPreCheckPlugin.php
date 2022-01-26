<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShoppingList\Business\ProductOfferShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShoppingList\ProductOfferShoppingListConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShoppingList\Communication\ProductOfferShoppingListCommunicationFactory getFactory()
 */
class ProductOfferShoppingListAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if product offer exists and refers to required product.
     * - Checks if product offer is active.
     * - Checks if product offer approval status is 'approved'.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->checkProductOfferShoppingListItem($shoppingListItemTransfer);
    }
}
