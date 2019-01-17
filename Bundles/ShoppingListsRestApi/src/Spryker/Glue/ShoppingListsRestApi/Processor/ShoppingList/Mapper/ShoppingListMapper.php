<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper;

use Generated\Shared\Transfer\RestShoppingListAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

class ShoppingListMapper implements ShoppingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListAttributesTransfer
     */
    public function mapShoppingListTransferToRestShoppingListsAttributesTransfer(
        ShoppingListTransfer $shoppingListTransfer,
        RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
    ): RestShoppingListAttributesTransfer {
        $restShoppingListAttributesTransfer->fromArray($shoppingListTransfer->toArray(), true);

        return $restShoppingListAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function mapRestShoppingListAttributesTransferToRestShoppingListRequestTransfer(
        RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer,
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): RestShoppingListRequestTransfer {
        $restShoppingListRequestTransfer->getShoppingList()->fromArray(
            $restShoppingListAttributesTransfer->toArray(),
            true
        );

        return $restShoppingListRequestTransfer;
    }
}
