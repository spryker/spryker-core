<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface QuoteItemToItemMapperPluginInterface
{
    /**
     * Specification:
     * - Maps QuoteItemTransfer properties to ItemTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function map(ItemTransfer $quoteItemTransfer, ItemTransfer $itemTransfer): ItemTransfer;
}
