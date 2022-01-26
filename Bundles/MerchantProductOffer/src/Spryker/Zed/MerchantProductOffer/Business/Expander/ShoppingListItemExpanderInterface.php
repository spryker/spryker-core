<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Expander;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;

interface ShoppingListItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemCollectionWithMerchantReference(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;
}
