<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ServicePointCartFacadeInterface
{
    /**
     * Specification:
     * - Executes {@link \Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface} strategy plugins.
     * - Replaces quote items using applicable strategy.
     * - Reloads cart items if the execution of strategy plugins was successful.
     * - Returns `QuoteReplacementResponseTransfer.quote.items` with replaced items.
     * - Adds `QuoteErrorTransfer` to `QuoteReplacementResponseTransfer.errors` if applicable substitution has not been replaced.
     * - Adds `QuoteTransfer.item` to `QuoteReplacementResponseTransfer.failedReplacementItems` if substitution for applicable item was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer;

    /**
     * Specification:
     * - Expects `QuoteTransfer.items.servicePoint` to be provided.
     * - Requires `QuoteTransfer.store.name` and `QuoteTransfer.items.servicePoint.uuid` to be provided if `QuoteTransfer.items.servicePoint` is provided.
     * - Checks if `QuoteTransfer.items.servicePoint` are active and available for the current store.
     * - Sets `CheckoutResponseTransfer.isSuccess` = `false` if any of the service points is inactive or unavailable for the current store.
     * - Adds `CheckoutResponseTransfer.checkoutError` with corresponding error message if any of the service points is inactive or unavailable for the current store.
     * - Returns `true` if all service points are active and available for the current store, `false` otherwise.
     * - Returns `true` if no service points are provided in `QuoteTransfer.items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutQuoteItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;
}
