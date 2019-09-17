<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteStorageStrategyPluginInterface
{
    /**
     * Specification:
     * - Gets quote storage strategy type
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();

    /**
     * Specification:
     * - Adds single item
     * - Makes zed request.
     * - Returns update quote.
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
     * - Adds multiple items (identified by SKU and quantity)
     * - Makes zed request to stored cart into persistent store if used.
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
     * - Adds only items, that passed cart validation.
     * - Makes zed request to stored cart into persistent store if used.
     * - Adds notification to response.
     * - Returns updated quote.
     * - Quote not saved.
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
     *  - Updates quantity for each item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;

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
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @return void
     */
    public function reloadItems();

    /**
     * Specification:
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles if quote is not locked.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote();

    /**
     * Specification:
     * - Set currency to quote.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer;
}
