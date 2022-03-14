<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOffer\MerchantProductOfferConfig getConfig()
 */
class MerchantProductOfferAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that merchant of product offer is active.
     * - Validates that merchant of product offer is approved.
     * - Uses `ShoppingListItemTransfer.productOfferReference` to find corresponding product offer.
     * - Skips validation if `ShoppingListItemTransfer.productOfferReference` is not provided.
     * - Skips validation if product offer is not found by `ShoppingListItemTransfer.productOfferReference` provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->checkShoppingListItem($shoppingListItemTransfer);
    }
}
