<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointCart;

use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ServicePointCartClientInterface
{
    /**
     * Specification:
     * - Makes Zed call.
     * - Executes {@link \Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface} strategy plugins.
     * - Replaces quote items using applicable strategy.
     * - Reloads cart items if the execution of strategy plugins was successful.
     * - Returns `QuoteReplacementResponseTransfer.quote.items` with replaced items.
     * - Adds `QuoteErrorTransfer` to `QuoteReplacementResponseTransfer.errors` if applicable product offer has not been replaced.
     * - Adds `QuoteTransfer.item` to `QuoteReplacementResponseTransfer.failedReplacementItems` if product offer for applicable item was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer;
}
