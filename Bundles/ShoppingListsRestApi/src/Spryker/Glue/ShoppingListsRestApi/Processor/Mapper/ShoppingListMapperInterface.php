<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShoppingListsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListsAttributesTransfer $restShoppingListsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListsAttributesTransfer
     */
    public function mapShoppingListTransferToRestShoppingListsAttributesTransfer(
        ShoppingListTransfer $shoppingListTransfer,
        RestShoppingListsAttributesTransfer $restShoppingListsAttributesTransfer
    ): RestShoppingListsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListRequestTransfer $shoppingListRequestTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapRestShoppingListsAttributesTransferToShoppingListTransfer(
        ShoppingListRequestTransfer $shoppingListRequestTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer;
}
