<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListItemMapper implements ShoppingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer
     */
    public function mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestShoppingListItemAttributesTransfer {
        return $restShoppingListItemAttributesTransfer->fromArray(
            $shoppingListItemTransfer->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function mapRestShoppingListItemAttributesTransferToRestShoppingListItemRequestTransfer(
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer,
        RestShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): RestShoppingListItemRequestTransfer {
        $shoppingListItemRequestTransfer->getShoppingListItem()->fromArray(
            $restShoppingListItemAttributesTransfer->toArray(),
            true
        );

        return $shoppingListItemRequestTransfer;
    }
}
