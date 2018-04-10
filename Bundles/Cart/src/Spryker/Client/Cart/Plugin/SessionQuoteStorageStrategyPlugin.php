<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\Cart\CartClientInterface getClient()
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class SessionQuoteStorageStrategyPlugin extends AbstractPlugin implements QuoteStorageStrategyPluginInterface
{
    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_SESSION;
    }

    /**
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
     * @return void
     */
    public function reloadItems()
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer = $this->getCartZedStub()->reloadItems($quoteTransfer);
        $this->getQuoteClient()->setQuote($quoteTransfer);
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

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote()
    {
        $quoteResponseTransfer = $this->getCartZedStub()->validateQuote($this->getQuote());
        $this->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }
}
