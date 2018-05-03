<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartClientInterface
{
    /**
     * Specification:
     *  - Gets current quote from session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * Specification:
     * - Empty existing quote and store to session.
     * - In case of persistent strategy the quote is also deleted from database.
     *
     * @api
     *
     * @return void
     */
    public function clearQuote();

    /**
     * Specification:
     * - Session strategy:
     *   - Adds multiple items.
     *   - Makes zed request.
     *   - Adds only items, that passed cart validation.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Adds only items, that passed validation.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer;

    /**
     * Specification:
     * - Session strategy:
     *   - Adds items.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with item and customer.
     *   - Loads customer quote from database.
     *   - Adds item to quote.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer, array $params = []);

    /**
     * Specification:
     * - Session strategy:
     *   - Adds multiple items.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Adds items to quote.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers, array $params = []);

    /**
     * Specification:
     * - Session strategy:
     *   - Removes single items from quote.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Removes single item from quote.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null);

    /**
     * Specification:
     *  - Returns the calculated number of items in cart
     *
     * @api
     *
     * @return int
     */
    public function getItemCount();

    /**
     * Specification:
     * - Session strategy:
     *   - Removes single items from quote.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Removes items from quote.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items);

    /**
     * Specification:
     * - Session strategy:
     *   - Changes quantity for given item.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Changes quantity for given item.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1);

    /**
     * Specification:
     * - Session strategy:
     *   - Decreases quantity for given item.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Decreases quantity for given item.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1);

    /**
     * Specification:
     * - Session strategy:
     *   - Increases quantity for given item.
     *   - Makes zed request.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request with items and customer.
     *   - Loads customer quote from database.
     *   - Increases quantity for given item.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1);

    /**
     * Specification:
     *  - Store current quote into session
     *
     * @api
     *
     * @deprecated Use QuoteClient::setQuote() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Session strategy:
     *   - Makes zed request.
     *   - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request.
     *   - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @return void
     */
    public function reloadItems();

    /**
     * Specification:
     * - Session strategy:
     *   - Makes zed request.
     *   - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *   - Add changes as notices to messages
     *   - Check error messages
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request.
     *   - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *   - Add changes as notices to messages
     *   - Check error messages
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote();

    /**
     * Specification:
     * - Session strategy:
     *   - Set currency to quote.
     *   - Makes zed request.
     *   - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * - Persistent strategy:
     *   - Makes zed request.
     *   - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *   - Recalculates quote totals.
     *   - Save updated quote to database.
     *   - Stores quote in session internally after zed request.
     *   - Returns update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Takes array of MessageTransfers for the last response and push them to flash messages.
     *
     * @api
     *
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * Specification:
     * - Finds item in quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findQuoteItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer;
}
