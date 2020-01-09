<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper;

use Generated\Shared\Transfer\RestShoppingListAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListMapperInterface
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
    ): RestShoppingListAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function mapRestShoppingListAttributesTransferToRestShoppingListRequestTransfer(
        RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer,
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): RestShoppingListRequestTransfer;
}
