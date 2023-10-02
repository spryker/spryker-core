<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ClickAndCollectExampleFacadeInterface
{
    /**
     * Specification:
     * - Requires `QuoteTransfer.store.name` to be set.
     * - Requires `QuoteTransfer.currency.code`to be set.
     * - Requires `QuoteTransfer.priceMode`to be set.
     * - Requires `ItemTransfer.name` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.sku` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.quantity` for each `QuoteTransfer.item` to be set.
     * - Filters applicable `QuoteTransfer.items` for product offer replacement.
     * - Fetches available replacement product offers from Persistence.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Returns `QuoteResponseTransfer` with modified `QuoteTransfer.items` if replacements take place.
     * - Adds `QuoteErrorTransfer` to `QuoteResponseTransfer.errors` if applicable product offers have not been replaced.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replacePickupQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.store.name` to be set.
     * - Requires `QuoteTransfer.currency.code`to be set.
     * - Requires `QuoteTransfer.priceMode`to be set.
     * - Requires `ItemTransfer.name` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.sku` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.quantity` for each `QuoteTransfer.item` to be set.
     * - Filters applicable `QuoteTransfer.items` for product offer replacement.
     * - Fetches available replacement product offers from Persistence.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Returns `QuoteResponseTransfer` with modified `QuoteTransfer.items` if replacements take place.
     * - Adds `QuoteErrorTransfer` to `QuoteResponseTransfer.errors` if applicable product offers have not been replaced.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceDeliveryQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
