<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 * TODO: all public method need to do the similar as we did in QuoteClient (e.g. $this->createStrategyProvider()->x())
 */
class CartClient extends AbstractClient implements CartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: get from session all the time
     */
    public function getQuote()
    {
        return $this->getQuoteClient()->getQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO: delegate to QuoteClient::clearQuote()
     */
    public function clearQuote()
    {
        $this->getQuoteClient()->syncQuote();
        $this->getQuoteClient()->clearQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO: not needed
     */
    public function syncQuote()
    {
        $this->getQuoteClient()->syncQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     * TODO: get from session all the time
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     * TODO: deprecate this method and remove usages in core
     */
    public function storeQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getQuoteClient()->setQuote($quoteTransfer);
        $this->getQuoteClient()->pushQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO:
     * - session strategy: additionally stores quote in session internally after zed request
     * - persistent strategy: make zed request with $itemTransfer as parameter and do everything in zed side (read quote from DB, prepare CartChangeTransfer, recalculate, store quote in db) then store in session after zed request
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        return $this->getZedStub()->addItem($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
     */
    public function addItems(array $itemTransfers)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }

        return $this->getZedStub()->addItem($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
     */
    public function removeItem($sku, $groupKey = null)
    {
        $itemTransfer = $this->findItem($sku, $groupKey);
        if (!$itemTransfer) {
            return $this->getQuote();
        }

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
     */
    public function removeItems(ArrayObject $items)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->setItems($items);

        return $this->getZedStub()->removeItem($cartChangeTransfer);
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

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
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

        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: similar as addItem()
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

        return $this->getZedStub()->addItem($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO: similar as addItem()
     */
    public function reloadItems()
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer = $this->getZedStub()->reloadItems($quoteTransfer);
        $this->storeQuote($quoteTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $this->getQuoteClient()->syncQuote();
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
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
}
