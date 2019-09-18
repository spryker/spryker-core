<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\Cart\Business\CartBusinessFactory getFactory()
 */
interface CartFacadeInterface
{
    /**
     *  Adds only valid item(s) to the quote. Each item gets additional information (e.g. price).
     *
     * Specification:
     *  - Does nothing if cart is locked.
     *  - Run cart pre check plugins, per every item.
     *  - Add to cart only valid items.
     *  - If some items relay on one stock - items will be added by same order, until stock allow it.
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Add new item(s) to quote (requires, but not limited, a quantity > 0 for each new item)
     *  - Group items in quote (-> ItemGrouper)
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Return updated quote if quote is not locked.
     *  - Adds messenger error message and returns unchanged QuoteTransfer if quote is locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValid(CartChangeTransfer $cartChangeTransfer): QuoteTransfer;

    /**
     *  Adds item(s) to the quote. Each item gets additional information (e.g. price).
     *
     * Specification:
     *  - Does nothing if cart is locked.
     *  - Run cart pre check plugins
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Add new item(s) to quote (requires, but not limited, a quantity > 0 for each new item)
     *  - Group items in quote (-> ItemGrouper)
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Return updated quote if quote is not locked.
     *  - Adds messenger error message and returns unchanged QuoteTransfer if quote is locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     *  - Adds item(s) to the quote. Each item gets additional information (e.g. price).
     *  - Does nothing if cart is locked.
     *  - Runs cart pre check plugins.
     *  - For each new item runs the item expander plugins (requires a SKU for each new item).
     *  - Adds new item(s) to quote (requires, but not limited, a quantity > 0 for each new item).
     *  - Groups items in quote.
     *  - Recalculates quote.
     *  - Adds success message to messenger.
     *  - Returns QuoteResponse with updated quote if quote is not locked.
     *  - In case of error adds messenger error message and returns QuoteResponse with unchanged QuoteTransfer and errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Decreases the given quantity for the given item(s) from the quote
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Return updated quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     *  - For each new item run the item expander plugins (requires a SKU for each new item).
     *  - Decreases the given quantity for the given item(s) from the quote.
     *  - Recalculates quote (-> Calculation).
     *  - Adds success message to messenger (-> Messenger).
     *  - Returns updated quote.
     *  - Returns QuoteResponse with updated quote if quote is not locked.
     *  - In case of error adds messenger error message and returns QuoteResponse with unchanged QuoteTransfer and errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Uses CartFacade::validateQuote before reloading
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles if quote is not locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *  - Check changes and add notes to messenger (-> Messenger)
     *  - Returns with unchanged QuoteTransfer and `isSuccessful=true` when cart is locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Clean ups quote items key group prefix.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function cleanUpItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
