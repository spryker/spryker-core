<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacade getFacade()
 * @method \Spryker\Zed\MerchantProductOffer\MerchantProductOfferConfig getConfig()
 */
class MerchantProductOfferShoppingListItemCollectionExpanderPlugin extends AbstractPlugin implements ShoppingListItemCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands a {@link \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer} with Merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandItemCollection(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->getFacade()
            ->expandShoppingListItemCollection($shoppingListItemCollectionTransfer);
    }
}
