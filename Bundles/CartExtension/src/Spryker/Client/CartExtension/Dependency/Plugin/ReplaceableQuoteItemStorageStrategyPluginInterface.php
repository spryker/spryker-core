<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

/**
 * Use this plugin to provide cart item replace functionality.
 */
interface ReplaceableQuoteItemStorageStrategyPluginInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Removes `ItemReplaceTransfer::itemToBeReplaced` from the quote.
     * - Adds `ItemReplaceTransfer::newItem` to quote.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemReplaceTransfer $itemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(ItemReplaceTransfer $itemReplaceTransfer): QuoteResponseTransfer;
}
