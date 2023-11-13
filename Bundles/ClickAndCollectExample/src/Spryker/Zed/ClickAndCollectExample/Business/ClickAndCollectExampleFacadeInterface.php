<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
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
     * - Requires `ItemTransfer.servicePoint.IdServicePoint` for each `QuoteTransfer.item` to be set in case pickup shipment type.
     * - Filters applicable `QuoteTransfer.items` for product offer replacement.
     * - Merges filtered items quantity by sku and merchant reference.
     * - Fetches available replacement product offers from Persistence.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Returns `QuoteReplacementResponseTransfer` with modified `QuoteTransfer.items` if replacements take place.
     * - Adds `QuoteErrorTransfer` to `QuoteReplacementResponseTransfer.errors` if applicable product offers have not been replaced.
     * - Adds QuoteTransfer.item.groupKey to QuoteReplacementResponseTransfer.failedItemGroupKeys if the product offer for the applicable item was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer;

    /**
     * Specification:
     * - Requires `CheckoutDataTransfer.quote` to be set.
     * - Collects product offers with service point, shipment, shipment type and shipment method.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Does not modify original quote.
     * - Returns errors in case any of items can not be replaced.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateQuoteItemProductOfferReplacement(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;
}
