<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ServicePointCartClientInterface
{
    /**
     * Specification:
     * - Makes Zed call.
     * - Executes {@link \Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface} strategy plugins.
     * - Replaces quote items using applicable strategy.
     * - Reloads cart items if the execution of strategy plugins was successful.
     * - Updates quote using `CartClient` after all changes.
     * - Returns `QuoteResponseTransfer.quoteTransfer.items` with replaced items.
     * - Returns `QuoteResponseTransfer.quoteTransfer.isSuccessful` false if any of the applied strategies fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
