<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartOperationQuoteStorageStrategyPluginInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteResetLockQuoteStorageStrategyPluginInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\Cart\CartClientInterface getClient()
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class SessionQuoteStorageStrategyPlugin extends AbstractPlugin implements QuoteStorageStrategyPluginInterface, QuoteResetLockQuoteStorageStrategyPluginInterface, CartOperationQuoteStorageStrategyPluginInterface
{
    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_SESSION;
    }

    /**
     * Specification:
     *  - Adds items.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns updated quote if quote is not locked.
     *  - Adds messenger error message and returns unchanged QuoteTransfer if quote is locked.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer, array $params = [])
    {
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->addItemsRequestExpand($cartChangeTransfer, $params);

        $quoteTransfer = $this->getCartZedStub()->addItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Makes zed request.
     *  - Adds items to quote.
     *  - Recalculates quote totals.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers, array $params = [])
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }

        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->addItemsRequestExpand($cartChangeTransfer, $params);

        $quoteTransfer = $this->getCartZedStub()->addItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Adds multiple items.
     *  - Makes zed request.
     *  - Adds only items, that passed cart validation.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote if quote is not locked.
     *  - Adds messenger error message and returns unchanged QuoteTransfer if quote is locked.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer
    {
        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->addItemsRequestExpand($cartChangeTransfer, $params);

        $quoteTransfer = $this->getCartZedStub()->addValidItems($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Removes single items from quote.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        $itemTransfer = $this->findItem($sku, $groupKey);
        if (!$itemTransfer) {
            return $this->getQuote();
        }

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->removeItemRequestExpand($cartChangeTransfer);

        $quoteTransfer = $this->getCartZedStub()->removeItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Removes single items from quote.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->setItems($items);
        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->removeItemRequestExpand($cartChangeTransfer);

        $quoteTransfer = $this->getCartZedStub()->removeItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Changes quantity for given item.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($sku, $groupKey);
        }

        $itemTransfer = $this->findItem($sku, $groupKey);
        if (!$itemTransfer) {
            return $this->getQuote();
        }

        $delta = abs($itemTransfer->getQuantity() - $quantity);

        if ($delta === 0) {
            return $this->getQuoteClient()->getQuote();
        }

        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($sku, $groupKey, $delta);
        }

        return $this->increaseItemQuantity($sku, $groupKey, $delta);
    }

    /**
     * Specification:
     * - Makes zed request.
     * - Adds items to quote.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartOperation()
            ->addToCart($cartChangeTransfer);
    }

    /**
     * Specification:
     * - Makes zed request.
     * - Adds items to quote.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartOperation()
            ->removeFromCart($cartChangeTransfer);
    }

    /**
     * Specification:
     * - Makes zed request.
     * - Updates quantity for given items.
     * - Stores quote in session internally after successful zed request.
     * - Returns response with updated quote.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartOperation()
            ->updateQuantity($cartChangeTransfer);
    }

    /**
     * Specification:
     *  - Decreases quantity for given item.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $decreaseItemTransfer = $this->findItem($sku, $groupKey);
        if (!$decreaseItemTransfer) {
            return $this->getQuote();
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        $cartChangeTransfer = $this->getFactory()
            ->createCartChangeRequestExpander()
            ->removeItemRequestExpand($cartChangeTransfer);

        $quoteTransfer = $this->getCartZedStub()->removeItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Specification:
     *  - Increases quantity for given item.
     *  - Makes zed request.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $increaseItemTransfer = $this->findItem($sku, $groupKey);
        if (!$increaseItemTransfer) {
            return $this->getQuote();
        }

        $itemTransfer = clone $increaseItemTransfer;
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        $quoteTransfer = $this->getCartZedStub()->addItem($cartChangeTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getQuoteClient()->setQuote($quoteTransfer);
    }

    /**
     * Specification:
     *  - Makes zed request.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @return void
     */
    public function reloadItems()
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer = $this->getCartZedStub()->reloadItems($quoteTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);
    }

    /**
     * Specification:
     *  - Makes zed request.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Adds changes as notices to messages
     *  - Check error messages
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *  - Returns with unchanged QuoteTransfer and 'isSuccessful=true' when cart is locked.
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote()
    {
        $quoteResponseTransfer = $this->getCartZedStub()->validateQuote($this->getQuote());
        $this->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * Specification:
     *  - Sets currency to quote.
     *  - Makes zed request.
     *  - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer->setCurrency($currencyTransfer);
        if (count($quoteTransfer->getItems())) {
            $quoteTransfer = $this->getCartZedStub()->reloadItems($quoteTransfer);
        }
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        if (count($this->getFactory()->getZedRequestClient()->getResponsesErrorMessages()) === 0) {
            $quoteResponseTransfer->setIsSuccessful(true);
            $this->getQuoteClient()->setQuote($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * Specification:
     * - Makes zed request.
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     * - Stores quote in session internally after zed request.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->getCartZedStub()
            ->resetQuoteLock($this->getQuote());

        $this->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItem($sku, $groupKey = null)
    {
        $quoteTransfer = $this->getQuote();

        return $this->getFactory()
            ->getQuoteItemFinderPlugin()
            ->findItem($quoteTransfer, $sku, $groupKey);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $quoteTransfer = $this->getQuoteClient()->getQuote();
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
        }

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function prepareCartChangeTransfer(ItemTransfer $itemTransfer)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    protected function getCartZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected function getQuoteClient()
    {
        return $this->getFactory()->getQuoteClient();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote()
    {
        return $this->getQuoteClient()->getQuote();
    }
}
