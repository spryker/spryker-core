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
     *  - Get current quote from session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * Specification:
     *  - Empty existing quote and store to session
     *
     * @api
     *
     * @return void
     */
    public function clearQuote();

    /**
     * Specification:
     * - Adds single item
     * - Makes zed request.
     * - Returns update quote.
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
     * - Adds multiple items (identified by SKU and quantity)
     * - Makes zed request to stored cart into persistent store if used.
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
     *  - Removes single items from quote.
     *  - Makes zed request.
     *  - Returns update quote.
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
     *  - Removes all given items from quote.
     *  - Makes zed request.
     *  - Returns update quote.
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
     *  - Changes quantity for given item.
     *  - Makes zed request.
     *  - Returns updated quote.
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
     *  - Decreases quantity for given item.
     *  - Makes zed request.
     *  - Returns updated quote.
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
     *  - Increases quantity for given item.
     *  - Makes zed request.
     *  - Returns updated quote.
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @return void
     */
    public function reloadItems();
}
