<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper;

use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function mapShoppingListCollectionTransferToRestShoppingListCollectionResponseTransfer(
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer,
        RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
    ): RestShoppingListCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapShoppingListResponseTransferToShoppingListTransfer(
        ShoppingListResponseTransfer $shoppingListResponseTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function mapShoppingListResponseErrorsToRestCodes(
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer;
}
