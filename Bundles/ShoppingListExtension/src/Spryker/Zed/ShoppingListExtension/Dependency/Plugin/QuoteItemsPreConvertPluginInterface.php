<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteItemsPreConvertPluginInterface
{
    /**
     * Specification:
     *  - Prepare quote item collection before adding them to shopping list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function expand(ItemCollectionTransfer $itemCollectionTransfer, QuoteTransfer $quoteTransfer): ItemCollectionTransfer;
}
