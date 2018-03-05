<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
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
     * - Session strategy: clear quote in session.
     * - Persistent strategy: removes current quote from DB and session.
     *
     * @api
     *
     * @return void
     */
    public function clearQuote();

    /**
     * Specification:
     * - Session strategy:
     *   - Adds single item.
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
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

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
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers);

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
     * @deprecated
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
}
