<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

/**
 * Provides ability to map additional data to `RestShoppingListItemsAttributes` transfer object.
 */
interface RestShoppingListItemsAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps additional data to `RestShoppingListItemsAttributes` transfer object.
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
    ): RestShoppingListItemsAttributesTransfer;
}
