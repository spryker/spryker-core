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
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\PermissionAwareTrait;

/**
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class CartClient extends AbstractClient implements CartClientInterface
{
    use PermissionAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getQuoteClient()->getQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function clearQuote()
    {
        $this->getQuoteClient()->clearQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getItemCount()
    {
        return $this->getItemCounter()->getItemCount($this->getQuote());
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface
     */
    protected function getItemCounter()
    {
        return $this->getFactory()->getItemCounter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use QuoteClient::setQuote() instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->addValidItems($cartChangeTransfer, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer, array $params = [])
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->addItem($itemTransfer, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers, array $params = [])
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->addItems($itemTransfers, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->removeItem($sku, $groupKey);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items)
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->removeItems($items);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->changeItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->decreaseItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->increaseItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function reloadItems()
    {
        $this->getFactory()->createQuoteStorageStrategyProxy()->reloadItems();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote()
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->validateQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateSpecificQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createZedStub()
            ->validateQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->setQuoteCurrency($currencyTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest()
    {
        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated
     *
     * @return \Spryker\Client\Cart\Zed\CartStubInterface|\Spryker\Client\ZedRequest\Stub\ZedRequestStub
     */
    public function getZedStub()
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findQuoteItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        return $this->getFactory()->getQuoteItemFinderPlugin()->findItem($quoteTransfer, $sku, $groupKey);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteStorageStrategyProxy()->resetQuoteLock();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->lockQuote($quoteTransfer);
    }
}
