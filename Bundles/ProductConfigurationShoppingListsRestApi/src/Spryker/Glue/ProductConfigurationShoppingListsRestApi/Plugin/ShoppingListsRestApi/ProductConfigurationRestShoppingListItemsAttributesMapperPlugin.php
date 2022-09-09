<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi\Plugin\ShoppingListsRestApi;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin\RestShoppingListItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiFactory getFactory()
 */
class ProductConfigurationRestShoppingListItemsAttributesMapperPlugin extends AbstractPlugin implements RestShoppingListItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ShoppingListItemTransfer` product configuration to `RestShoppingListItemsAttributesTransfer`.
     * - Executes {@link \Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer
     */
    public function map(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
    ): RestShoppingListItemsAttributesTransfer {
        return $this->getFactory()
            ->createProductConfigurationRestShoppingListItemsAttributesMapper()
            ->mapShoppingListItemTransferToRestShoppingListItemsAttributesTransfer(
                $shoppingListItemTransfer,
                $restShoppingListItemsAttributesTransfer,
            );
    }
}
