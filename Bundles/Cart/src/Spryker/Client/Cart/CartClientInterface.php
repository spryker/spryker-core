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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Adds items to cart using quote storage strategy.
     *  - Invalid items will be skipped.
     *  - Returns the unchanged QuoteTransfer and CustomerTransfer with 'isSuccessful=false' when provided quote is locked.
     *  - Adds error message to Messenger when quote is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Adds item to cart using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Adds items to cart using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Remove item from cart using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Remove items from cart using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Change item quantity using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Uses quote storage strategy.
     *  - Adds items to cart.
     *  - Increases quantity for items that are already in cart.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Uses quote storage strategy.
     *  - Decreases quantity for items using the provided quantities.
     *  - Removes items from cart that reach 0 quantity.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Uses quote storage strategy.
     *  - Updates item quantity to the provided quantities.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Decrease item quantity using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Increase item quantity using quote storage strategy.
     *  - Does nothing if cart is locked.
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
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles using quote storage strategy.
     *  - Does nothing if cart is locked.
     *
     * @api
     *
     * @return void
     */
    public function reloadItems();

    /**
     * Specification:
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Reloads quote from storage.
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles using quote storage strategy if quote is not locked.
     *  - Adds messages about quote-related changes to Messenger.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote();

    /**
     * Specification:
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles if quote is not locked.
     *  - Adds messages about quote-related changes to Messenger.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateSpecificQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Resolve quote storage strategy which implements \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface.
     *  - Default quote storage strategy \Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin.
     *  - Update quote currency using quote storage strategy.
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *  - Returns the unchanged QuoteTransfer and CustomerTransfer with `isSuccessful=false` when provided quote is locked.
     *  - Adds error message to Messenger when quote is locked.
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

    /**
     * Specification:
     * - Makes zed request.
     * - Loads customer quote from database when storage strategy is in place.
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     * - Save updated quote to database when storage strategy is in place.
     * - Stores quote in session internally after zed request.
     *
     * @api
     *
     * @throws \Spryker\Client\Cart\Exception\QuoteStorageStrategyPluginNotFound if storage strategy does not implement `QuoteResetLockQuoteStorageStrategyPluginInterface`.
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Locks quote by setting `isLocked` transfer property to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
