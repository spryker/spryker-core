<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListItemMapper implements ShoppingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer
     */
    public function mapShoppingListItemTransferToRestShoppingListItemsAttributesTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
    ): RestShoppingListItemsAttributesTransfer {
        return $restShoppingListItemsAttributesTransfer->fromArray(
            $shoppingListItemTransfer->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function mapRestShoppingListItemsAttributesTransferToRestShoppingListItemRequestTransfer(
        RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer,
        RestShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): RestShoppingListItemRequestTransfer {
        $shoppingListItemRequestTransfer->getShoppingListItem()->fromArray(
            $restShoppingListItemsAttributesTransfer->modifiedToArray(),
            true
        );

        return $shoppingListItemRequestTransfer;
    }
}
