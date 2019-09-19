<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory getFactory()
 */
interface PersistentCartFacadeInterface
{
    /**
     *  Adds item(s) to the quote. Each item gets additional information (e.g. price).
     *
     * Specification:
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Runs cart pre check plugins.
     *  - For each new item runs the item expander plugins (requires a SKU for each new item).
     *  - Adds new item(s) to quote (Requires a quantity > 0 for each new item).
     *  - Groups items in quote (-> ItemGrouper).
     *  - Recalculates quote (-> Calculation).
     *  - Adds success message to messenger (-> Messenger).
     *  - Calls quote response extend plugins.
     *  - Returns updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    /**
     *  Adds only valid item(s) to the quote. Each item gets additional information (e.g. price).
     *
     * Specification:
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Runs cart pre check plugins, per every item.
     *  - Adds to cart only valid items.
     *  - If some items relay on one stock - items will be added by same order, until stock allow it.
     *  - For each new item runs the item expander plugins (requires a SKU for each new item).
     *  - Adds new item(s) to quote (requires, but not limited, a quantity > 0 for each new item).
     *  - Groups items in quote (-> ItemGrouper).
     *  - Recalculates quote (-> Calculation).
     *  - Add successs message to messenger (-> Messenger).
     *  - Calls quote response extend plugins.
     *  - Returns updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addValid(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - For each new item runs the item expander plugins (requires a SKU for each new item).
     *  - Decreases the given quantity for the given item(s) from the quote.
     *  - Recalculates quote (-> Calculation).
     *  - Adds success message to messenger (-> Messenger).
     *  - Calls quote response extend plugins.
     *  - Returns updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles if quote is not locked.
     *  - Call quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Loads quote from db.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Calls calculate quantity to add or remove.
     *  - Removes or add items.
     *  - Saves quote to DB.
     *  - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Loads quote from db.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Calls calculate quantity to add or remove.
     *  - Removes or add items.
     *  - Saves quote to DB in case success result.
     *  - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Loads quote from db.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Calls calculate quantity to remove.
     *  - Removes items from quote.
     *  - Saves quote to DB.
     *  - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Loads quote from db.
     *  - Merges loaded quote with quote from change request if is provided.
     *  - Calls calculate quantity to add.
     *  - Adds items to quote.
     *  - Saves quote to DB.
     *  - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - If there are no items in the quote then it creates a new empty quote and merges it with the provided quote as well as with the quote from DB for provided customer.
     * - If quote has items then it merges provided quote with quote from DB for provided customer.
     * - Saves quote to DB.
     * - Throws QuoteSynchronizationNotAvailable exception if database quote storage strategy is not used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - If quote is not locked reloads all items in quote as new, it recreates all items transfer, reads new prices, options, bundles.
     *  - Check changes and add notes to messenger (-> Messenger)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes quote from database.
     * - Executes update quote plugins.
     * - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Find customer quote.
     *  - Call quote response extend plugins.
     *
     * @api
     *
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuote(int $idQuote, CustomerTransfer $customerTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Saves quote in database.
     *  - Call quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Saves quote in database.
     *  - Call quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Creates quote in database.
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *  - Calls quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Load quote by id.
     *  - Add changes.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Saves quote in database.
     *  - Call quote response extend plugins.
     *  - Operation will be performed only if customer has permission to update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateAndReloadQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Retrieves a quote from Persistence using the provided customer and store information.
     * - Replaces the retrieved quote with the provided quote and stores it in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Load quote by id.
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     * - Saves quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
