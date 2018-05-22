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
     *  - Run cart pre check plugins
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Add new item(s) to quote (Requires a quantity > 0 for each new item)
     *  - Group items in quote (-> ItemGrouper)
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Call quote response extend plugins.
     *  - Return updated quote
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
     *  - Run cart pre check plugins, per every item.
     *  - Add to cart only valid items.
     *  - If some items relay on one stock - items will be added by same order, until stock allow it.
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Add new item(s) to quote (requires, but not limited, a quantity > 0 for each new item)
     *  - Group items in quote (-> ItemGrouper)
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Call quote response extend plugins.
     *  - Return updated quote
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
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Decreases the given quantity for the given item(s) from the quote
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Call quote response extend plugins.
     *  - Return updated quote
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
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
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
     *  - Load quote from db.
     *  - Call calculate quantity to add or remove.
     *  - Remove or add items.
     *  - Saves quote to DB.
     *  - Call quote response extend plugins.
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
     *  - Load quote from db.
     *  - Call calculate quantity to remove.
     *  - Remove items from quote.
     *  - Saves quote to DB.
     *  - Call quote response extend plugins.
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
     *  - Load quote from db.
     *  - Call calculate quantity to add.
     *  - Add items to quote.
     *  - Saves quote to DB.
     *  - Call quote response extend plugins.
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
     * - Merge provided quote with quote from DB for provided customer
     * - Saves quote to DB
     * - Throws QuoteSynchronizationNotAvailable exception if database quote storage strategy is not used
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
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
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
     *  - Remove quote from database
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
     *  - Load quote by id.
     *  - Add changes.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Saves quote in database.
     *  - Call quote response extend plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateAndReloadQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;
}
