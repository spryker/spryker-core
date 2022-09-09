<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi\Plugin\ShoppingListsRestApi;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin\ShoppingListItemRequestMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiFactory getFactory()
 */
class ProductConfigurationShoppingListItemRequestMapperPlugin extends AbstractPlugin implements ShoppingListItemRequestMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product configuration from rest attributes to shopping list item.
     * - Executes {@link \Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface} plugin stack.
     * - Requires `ShoppingListItemRequestTransfer.shoppingListItem` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemRequestTransfer
     */
    public function map(
        RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer,
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemRequestTransfer {
        return $this->getFactory()
            ->createProductConfigurationRestShoppingListItemsAttributesMapper()
            ->mapRestShoppingListItemsAttributesTransferToShoppingListItemRequestTransfer(
                $restShoppingListItemsAttributesTransfer,
                $shoppingListItemRequestTransfer,
            );
    }
}
