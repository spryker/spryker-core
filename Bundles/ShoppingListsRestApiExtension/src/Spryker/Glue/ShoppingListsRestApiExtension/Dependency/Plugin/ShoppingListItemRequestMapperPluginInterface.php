<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;

/**
 * Use this plugin to map additional data from `RestShoppingListItemsAttributesTransfer` to `ShoppingListItemRequestTransfer`.
 */
interface ShoppingListItemRequestMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `RestShoppingListItemsAttributesTransfer` to `ShoppingListItemRequestTransfer`.
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
    ): ShoppingListItemRequestTransfer;
}
